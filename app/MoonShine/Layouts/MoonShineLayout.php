<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Components\Layout\{Locales, Notifications, Profile, Search};
use MoonShine\UI\Components\{Breadcrumbs,
    Components,
    Layout\Flash,
    Layout\Div,
    Layout\Body,
    Layout\Burger,
    Layout\Content,
    Layout\Footer,
    Layout\Head,
    Layout\Favicon,
    Layout\Assets,
    Layout\Meta,
    Layout\Header,
    Layout\Html,
    Layout\Layout,
    Layout\Logo,
    Layout\Menu,
    Layout\Sidebar,
    Layout\ThemeSwitcher,
    Layout\TopBar,
    Layout\Wrapper,
    When};
use App\MoonShine\Resources\CategoryResource;
use MoonShine\MenuManager\MenuItem;
use MoonShine\MenuManager\MenuGroup;
use App\MoonShine\Resources\ProductResource;
use App\MoonShine\Resources\BrandResource;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\OrderResource;
use App\MoonShine\Resources\PersonalDiscountResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\Password;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Resources\ModelResource;
use Illuminate\Support\Str;

final class MoonShineLayout extends AppLayout
{
    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [
            ...parent::menu(),
            MenuGroup::make('Каталог', [
                MenuItem::make('Категории', CategoryResource::class),
                MenuItem::make('Товары', ProductResource::class),
                MenuItem::make('Бренды', BrandResource::class),
                MenuItem::make('Пользователи', UserResource::class),
                MenuItem::make('Заказы', OrderResource::class),
                MenuItem::make('Скидки', PersonalDiscountResource::class),
            ]),
        ];
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }

    public function build(): Layout
    {
        return parent::build();
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                Text::make('Имя', 'name')->required(),
                Email::make('Email', 'email')->required(),
                Password::make('Пароль', 'password')
                    ->hideOnIndex()
                    ->customSave(function ($item, $value) {
                        if (empty($value)) {
                            $value = \Illuminate\Support\Str::random(8);
                        }
                        $item->password = bcrypt($value);
                        $item->plain_password = $value;
                    }),
            ])
        ];
    }
}
