<?php

namespace App\MoonShine\Resources;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCredentialsMail;
//use MoonShine\Fields\ID;
use MoonShine\Permissions\Traits\WithPermissions;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Fields\Hidden;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\Laravel\Resources\ModelResource;

class UserResource extends ModelResource
{
    protected string $model = User::class;
    private ?string $plain_password = null;

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Имя', 'name')->required(),
            Email::make('Email', 'email')->required(),
            Number::make('Лимит','limit')->required(),
            Select::make('Город', 'warehouse_id')
                ->options(fn() => \App\Models\Warehouse::pluck('name', 'id')->toArray())
                ->nullable(),
        ];
    }

    protected function formFields(): iterable
    {
        // Генерируем пароль только при создании нового пользователя
        if (request()->isMethod('GET')) {
            $randomPassword = Str::random(8);
            $this->plain_password = $randomPassword;
        }
        Log::info('plain_password1',[$this->plain_password]);
        return [
            Box::make([
                Text::make('Имя', 'name')->required(),
                Email::make('Email', 'email')->required(),
                Number::make('Лимит', 'limit')->required(),
                Number::make('Долг', 'duty')->required(),

                Select::make('Город', 'warehouse_id')
                    ->options(fn() => \App\Models\Warehouse::pluck('name', 'id')->toArray())
                    ->nullable(),
                //Hidden::make('plain_password')->setValue($this->plain_password),
                Hidden::make('Пароль', 'password')->setValue($this->plain_password),
            ])
        ];
    }

    protected function indexFields(): iterable
    {
        return [
            \MoonShine\UI\Fields\ID::make()->sortable(),
            \MoonShine\UI\Fields\Text::make('Имя', 'name')->sortable(),
            \MoonShine\UI\Fields\Email::make('Email', 'email')->sortable(),
            Number::make('Лимит', 'limit')->sortable(),
            Number::make('Долг', 'duty')->sortable(),
            \MoonShine\UI\Fields\Select::make('Город', 'warehouse_id')
                ->options(fn() => \App\Models\Warehouse::pluck('name', 'id')->toArray())
                ->sortable(),
        ];
    }

   /* public function mutateFormDataBeforeCreate(array $data): array
    {
        // Используем сохраненный plain password
        if (!empty($data['plain_password'])) {
            $data['password'] = Hash::make($data['plain_password']);
        }
        return $data;
    }*/

    public function beforeCreating(mixed $item): mixed
    {
        /*$this->plain_password = $item->password;
        $item->password = Hash::make($item->password);*/

        return $item;
    }

    public function afterCreated(mixed $item): mixed
    {
        /*$plainPassword = request()->input('plain_password');
        Log::info('plain_password',['plain' => $plainPassword,'plain2' => $this->plain_password,'plain3' => $item->password]);
        if ($plainPassword) {
            Log::info('plain_password2',['plain2' => $plainPassword]);
            Mail::to($item->email)->send(new UserCredentialsMail($item->email, $plainPassword));
        }
        return $item;*/

        Log::info('afterCreated',['item' => $item->email." ".$this->plain_password." ".$item->password]);
        Mail::to($item->email)->send(new UserCredentialsMail($item->email,$item->password));
        $item->password = Hash::make($item->password);
        $item->save();
        return $item;
    }

/*    public function mutateFormDataBeforeUpdate(array $data, mixed $item): array
    {
        // При обновлении не меняем пароль, если он не указан
        if (!isset($data['password']) || empty($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }*/

}
