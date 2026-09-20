<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CarMark;
use App\Models\CarModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Справочник марок и моделей машин для мобильного приложения ASU Auto.
 *
 * Данные берутся из локальной БД (админка MoonShine), к 1С не обращаемся.
 */
class CarReferenceController extends Controller
{
    /**
     * GET /api/getMarks
     *
     * Возвращает все марки, отсортированные по названию.
     */
    public function getMarks(): JsonResponse
    {
        $marks = CarMark::query()
            ->orderBy('name')
            ->get()
            ->map(fn (CarMark $mark) => [
                'id' => $mark->id,
                'name' => $mark->name,
            ]);

        return response()->json($marks, 200);
    }

    /**
     * GET /api/getModels
     * GET /api/getModels?mark_id=1
     *
     * Без mark_id возвращает все модели, с mark_id — только модели этой марки.
     */
    public function getModels(Request $request): JsonResponse
    {
        $data = $request->validate([
            'mark_id' => ['nullable', 'integer', 'exists:car_marks,id'],
        ]);

        $models = CarModel::query()
            ->with('mark')
            ->when(
                isset($data['mark_id']),
                fn ($query) => $query->where('mark_id', $data['mark_id'])
            )
            ->orderBy('name')
            ->get()
            ->map(fn (CarModel $model) => [
                'id' => $model->id,
                'name' => $model->name,
                'markId' => $model->mark_id,
                'markName' => $model->mark?->name,
            ]);

        return response()->json($models, 200);
    }
}
