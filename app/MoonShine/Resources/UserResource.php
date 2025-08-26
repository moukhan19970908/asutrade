<?php

namespace App\MoonShine\Resources;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCredentialsMail;
use MoonShine\Fields\ID;
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
            Select::make('Город', 'warehouse_id')
                ->options(fn() => \App\Models\Warehouse::pluck('name', 'id')->toArray())
                ->nullable(),
        ];
    }

    protected function formFields(): iterable
    {
        // Генерируем пароль только при создании нового пользователя
        if (request()->isMethod('GET')) {
            $randomPassword = \Illuminate\Support\Str::random(8);
            $this->plain_password = $randomPassword;
        }
        
        return [
            Box::make([
                Text::make('Имя', 'name')->required(),
                Email::make('Email', 'email')->required(),
                Select::make('Город', 'warehouse_id')
                    ->options(fn() => \App\Models\Warehouse::pluck('name', 'id')->toArray())
                    ->nullable(),
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
            \MoonShine\UI\Fields\Select::make('Город', 'warehouse_id')
                ->options(fn() => \App\Models\Warehouse::pluck('name', 'id')->toArray())
                ->sortable(),
        ];
    }

    public function mutateFormDataBeforeCreate(array $data): array
    {
        // Используем сохраненный plain password
        if ($this->plain_password) {
            $data['password'] = $this->plain_password;
        }
        
        return $data;
    }

    public function afterCreated(mixed $item): mixed
    {
        if ($this->plain_password) {
            Mail::to($item->email)->send(new UserCredentialsMail($item->email, $this->plain_password));
        }
        
        return $item;
    }

    public function mutateFormDataBeforeUpdate(array $data, mixed $item): array
    {
        // При обновлении не меняем пароль, если он не указан
        if (!isset($data['password']) || empty($data['password'])) {
            unset($data['password']);
        }
        
        return $data;
    }
} 