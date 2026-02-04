<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\CategoryResource;
use App\MoonShine\Resources\ProductResource;
use App\MoonShine\Resources\BrandResource;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\OrderResource;
use App\MoonShine\Resources\OrderItemResource;
use App\MoonShine\Resources\PersonalDiscountResource;
use MoonShine\Laravel\Models\MoonshineUser;
use MoonShine\Laravel\MoonShineAuth;
use MoonShine\MenuManager\MenuItem;
use MoonShine\Permissions\Traits\HasMoonShinePermissions;
use MoonShine\Permissions\Traits\WithPermissions;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine  $core
     * @param  MoonShineConfigurator  $config
     *
     */
    public function boot(CoreContract $core, ConfiguratorContract $config): void
    {
        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                CategoryResource::class,
                ProductResource::class,
                BrandResource::class,
                UserResource::class,
                OrderResource::class,
                OrderItemResource::class,
                PersonalDiscountResource::class,
            ])
            ->pages([
                ...$config->getPages(),
            ])
        ;
    }

    public function menu(): array
    {
        return [
            MenuItem::make('Каталог', [
                MenuItem::make('Категории', new CategoryResource())->canSee(fn() => MoonshineUser::$moonshine_user_role_id === 2),
                MenuItem::make('Товары', ProductResource::class),
                MenuItem::make('Бренд', BrandResource::class),
                MenuItem::make('Персональные скидки', new PersonalDiscountResource()),
            ]),
            MenuItem::make('Пользователи', [
                MenuItem::make('Пользователи', new UserResource()),
            ]),
            MenuItem::make('Заказы', [
                MenuItem::make('Заказы', new OrderResource()),
                MenuItem::make('Элементы заказов', new OrderItemResource()),
            ]),
        ];
    }
}
