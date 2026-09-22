<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\OilChange;
use App\Models\OnecUser;
use App\Models\OtpCode;
use App\Services\GreenApi\GreenApiClient;
use App\Services\OneC\OneCClient;
use App\Services\Otp\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Прокси к HTTP-сервису 1С AsuAutoV2 для мобильного приложения ASU Auto.
 *
 * Контракт запросов и ответов совпадает с документацией 1С:
 * коды состояния и тело ответа пробрасываются как есть.
 */
class OneCController extends Controller
{
    public function __construct(
        protected OneCClient $onec,
        protected GreenApiClient $greenApi,
        protected OtpService $otp,
    ) {}

    /**
     * GET /api/checkUser?phone=77771112233
     */
    public function checkUser(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        return $this->onec->checkUser($data['phone'])->toJsonResponse();
    }

    /**
     * POST /api/createUser
     *
     * Номер должен быть заранее подтверждён кодом из WhatsApp
     * (POST /api/sendOtp → POST /api/verifyOtp), иначе 403.
     *
     * Сохраняет клиента в локальной таблице onec_users, шлёт приветствие
     * в WhatsApp и уходит в 1С — её ответ пробрасывается наружу как есть.
     *
     * Необязательный firebase_token — токен устройства из FCM для push.
     *
     * Машины здесь не заводятся: для этого есть POST /api/createCar.
     */
    public function createUser(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'firebase_token' => ['nullable', 'string', 'max:512'],
        ]);

        $phone = preg_replace('/\D+/', '', $data['phone']) ?? '';

        if (config('services.otp.required_on_register') && ! $this->otp->verified($phone)) {
            return response()->json([
                'error' => 'Номер не подтверждён. Запросите код через /api/sendOtp и подтвердите его',
            ], 403);
        }

        // Подтверждение одноразовое: повторно создать клиента тем же кодом нельзя.
        $this->otp->consume($phone);

        OnecUser::create([
            'name' => $data['name'],
            'phone' => $phone,
            'firebase_token' => $data['firebase_token'] ?? null,
        ]);

        $this->sendWelcomeMessage($phone, $data['name']);

        return $this->onec->createUser($data['name'], $data['phone'])->toJsonResponse();
    }

    /**
     * POST /api/deleteAccount
     *
     * Удаляет аккаунт клиента и все его данные в этой БД: самого клиента,
     * его машины, привязанные к машинам замены масла и коды подтверждения.
     * Операция необратима.
     *
     * Номер должен быть подтверждён кодом из WhatsApp
     * (POST /api/sendOtp → POST /api/verifyOtp), иначе 403: без этого
     * удалить чужой аккаунт мог бы любой, кто знает номер телефона.
     *
     * В 1С данные остаются — у её сервиса нет метода удаления клиента.
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $phone = preg_replace('/\D+/', '', $data['phone']) ?? '';

        if (config('services.otp.required_on_delete') && ! $this->otp->verified($phone)) {
            return response()->json([
                'error' => 'Номер не подтверждён. Запросите код через /api/sendOtp и подтвердите его',
            ], 403);
        }

        $userIds = OnecUser::where('phone', $phone)->pluck('id');

        // Машина привязана к клиенту по user_id, но живёт и без него — по phone.
        $carIds = Car::where('phone', $phone)
            ->orWhereIn('user_id', $userIds)
            ->pluck('id');

        if ($userIds->isEmpty() && $carIds->isEmpty()) {
            return response()->json([
                'error' => 'Аккаунт не найден для указанного телефона',
            ], 404);
        }

        $deleted = DB::transaction(function () use ($phone, $userIds, $carIds) {
            $oilChanges = OilChange::whereIn('car_id', $carIds)->count();

            return [
                // oil_changes удаляются каскадом по внешнему ключу car_id.
                'cars' => Car::whereIn('id', $carIds)->delete(),
                'oilChanges' => $oilChanges,
                'account' => OnecUser::whereIn('id', $userIds)->delete(),
                'otpCodes' => OtpCode::where('phone', $phone)->delete(),
            ];
        });

        Log::info('Аккаунт удалён по запросу клиента', ['phone' => $phone] + $deleted);

        return response()->json([
            'status' => 'deleted',
            'phone' => $phone,
            'deleted' => $deleted,
        ], 200);
    }

    /**
     * Приветствие в WhatsApp после регистрации. Ошибка отправки не должна
     * ломать регистрацию — она только пишется в лог.
     */
    protected function sendWelcomeMessage(string $phone, string $name): void
    {
        if (! config('services.green_api.welcome_enabled') || ! $this->greenApi->configured()) {
            return;
        }

        $message = strtr((string) config('services.green_api.welcome_message'), [':name' => $name]);

        $response = $this->greenApi->sendMessage($phone, $message);

        if (! $response->successful()) {
            Log::warning('GreenAPI: приветствие не отправлено', [
                'phone' => $phone,
                'status' => $response->status,
                'body' => $response->data,
            ]);
        }
    }

    /**
     * GET /api/getLevels
     */
    public function getLevels(): JsonResponse
    {
        return $this->onec->getLevels()->toJsonResponse();
    }

    /**
     * GET /api/getHistory?phone=... | ?clientId=...
     */
    public function getHistory(Request $request): JsonResponse
    {
        $data = $request->validate([
            'clientId' => ['required_without:phone', 'nullable', 'string', 'max:64'],
            'phone' => ['required_without:clientId', 'nullable', 'string', 'max:20'],
        ]);

        return $this->onec->getHistory($data)->toJsonResponse();
    }

    /**
     * GET /api/findClientByQr?code=...
     */
    public function findClientByQr(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:255'],
        ]);

        return $this->onec->findClientByQr($data['code'])->toJsonResponse();
    }

    /**
     * POST /api/createCar
     *
     * Сохраняет автомобиль клиента в локальной БД. Если клиент с таким
     * телефоном есть в приложении — привязывает машину к нему.
     *
     * Машину можно завести двумя способами:
     *  - model_id из справочника (/api/getModels) — тогда name соберётся
     *    из марки и модели, если его не прислали;
     *  - name произвольным текстом, как раньше.
     */
    public function createCar(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'model_id' => ['required_without:name', 'nullable', 'integer', 'exists:car_models,id'],
            'name' => ['required_without:model_id', 'nullable', 'string', 'max:255'],
            'vin_code' => ['nullable', 'string', 'max:255'],
        ]);

        $phone = preg_replace('/\D+/', '', $data['phone']) ?? '';

        $model = isset($data['model_id'])
            ? CarModel::with('mark')->find($data['model_id'])
            : null;

        $car = Car::create([
            'user_id' => OnecUser::where('phone', $phone)->orderByDesc('id')->value('id'),
            'phone' => $phone,
            'name' => $data['name'] ?? $this->carNameFromModel($model),
            'model_id' => $model?->id,
            'vin_code' => $data['vin_code'] ?? null,
        ]);

        $car->setRelation('carModel', $model);

        return response()->json($this->carToArray($car), 201);
    }

    /**
     * Название машины из справочника: «Марка Модель».
     */
    protected function carNameFromModel(?CarModel $model): string
    {
        return trim(($model?->mark?->name ?? '').' '.($model?->name ?? ''));
    }

    /**
     * Единый формат машины в ответах API.
     */
    protected function carToArray(Car $car): array
    {
        return [
            'id' => $car->id,
            'userId' => $car->user_id,
            'phone' => $car->phone,
            'name' => $car->name,
            'modelId' => $car->model_id,
            'modelName' => $car->carModel?->name,
            'markId' => $car->carModel?->mark_id,
            'markName' => $car->carModel?->mark?->name,
            'vinCode' => $car->vin_code,
        ];
    }

    /**
     * GET /api/getOilChangeHistory?phone=... | ?clientId=...
     *
     * Возвращает из истории клиента 1С только замены масла
     * (записи с type = "Замена масла").
     */
    public function getOilChangeHistory(Request $request): JsonResponse
    {
        $data = $request->validate([
            'clientId' => ['required_without:phone', 'nullable', 'string', 'max:64'],
            'phone' => ['required_without:clientId', 'nullable', 'string', 'max:20'],
        ]);

        $response = $this->onec->getHistory($data);

        // При ошибке 1С пробрасываем её как есть.
        if (! $response->successful()) {
            return $response->toJsonResponse();
        }

        $oilChanges = array_values(array_filter(
            $response->data,
            fn ($item) => is_array($item) && ($item['type'] ?? null) === 'Замена масла',
        ));

        return response()->json($oilChanges, 200);
    }

    /**
     * GET /api/getCars?phone=77771112233
     *
     * Возвращает список автомобилей клиента по номеру телефона.
     */
    public function getCars(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $phone = preg_replace('/\D+/', '', $data['phone']) ?? '';

        $cars = Car::with('carModel.mark')
            ->where('phone', $phone)
            ->orderByDesc('id')
            ->get()
            ->map(fn (Car $car) => $this->carToArray($car));

        return response()->json($cars, 200);
    }

    /**
     * GET /api/getCarOilChanges?car_id=1&phone=77771112233
     *
     * Возвращает сохранённые в БД замены масла, привязанные к машине.
     * Телефон должен совпадать с телефоном машины.
     */
    public function getCarOilChanges(Request $request): JsonResponse
    {
        $data = $request->validate([
            'car_id' => ['required', 'integer', 'exists:cars,id'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $phone = preg_replace('/\D+/', '', $data['phone']) ?? '';

        $car = Car::where('id', $data['car_id'])
            ->where('phone', $phone)
            ->first();

        if (! $car) {
            return response()->json([
                'error' => 'Машина не найдена для указанного телефона',
            ], 404);
        }

        $oilChanges = $car->oilChanges()
            ->orderByDesc('id')
            ->get()
            ->map(fn (OilChange $item) => [
                'id' => $item->id,
                'carId' => $item->car_id,
                'number' => $item->number,
                'mileage' => $item->mileage,
                'created_at' => $item->created_at->toDateTimeString(),
            ]);

        return response()->json($oilChanges, 200);
    }

    /**
     * POST /api/createOilChange
     *
     * Привязывает замену масла (чек из 1С) к автомобилю. Номер чека
     * уникален — повторная отправка того же чека вернёт 409.
     */
    public function createOilChange(Request $request): JsonResponse
    {
        $data = $request->validate([
            'car_id' => ['required', 'integer', 'exists:cars,id'],
            'number' => ['required', 'string', 'max:255'],
            'mileage' => ['required', 'integer', 'min:0'],
        ]);

        if (OilChange::where('number', $data['number'])->exists()) {
            return response()->json([
                'error' => 'Замена масла с таким номером чека уже существует',
            ], 409);
        }

        $oilChange = OilChange::create($data);

        return response()->json([
            'id' => $oilChange->id,
            'carId' => $oilChange->car_id,
            'number' => $oilChange->number,
            'mileage' => $oilChange->mileage,
        ], 201);
    }
}
