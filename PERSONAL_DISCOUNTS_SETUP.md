# Настройка раздела "Персональные скидки" в админке

## Что было создано

### 1. Модель
- **Файл**: `app/Models/PersonalDiscount.php`
- **Описание**: Модель для работы с персональными скидками
- **Поля**:
  - `user_id` - ID пользователя
  - `product_id` - ID товара
  - `discount_percent` - Процент скидки (decimal 5,2)
  - `is_active` - Активна ли скидка (boolean)
  - `description` - Описание скидки (text, nullable)

### 2. Миграция
- **Файл**: `database/migrations/2025_08_04_071924_create_personal_discounts_table.php`
- **Описание**: Создает таблицу `personal_discounts` с внешними ключами
- **Особенности**: Уникальный индекс по `user_id` и `product_id` для предотвращения дублирования

### 3. Ресурс MoonShine
- **Файл**: `app/MoonShine/Resources/PersonalDiscountResource.php`
- **Описание**: Административный интерфейс для управления персональными скидками
- **Функции**:
  - Создание/редактирование/удаление скидок
  - Поиск по пользователям и товарам
  - Валидация процента скидки (0-100%)
  - Переключатель активности скидки

### 4. Конфигурация MoonShine
- **Файл**: `app/Providers/MoonShineServiceProvider.php`
- **Описание**: Добавлен ресурс в список ресурсов и пункт "Персональные скидки" в группу "Каталог"

### 5. Отношения в моделях
- **User.php**: Добавлено отношение `personalDiscounts()`
- **Product.php**: Добавлено отношение `personalDiscounts()` и методы для работы со скидками

### 6. Фабрика и сидер
- **Файл**: `database/factories/PersonalDiscountFactory.php`
- **Файл**: `database/seeders/PersonalDiscountSeeder.php`
- **Описание**: Для создания тестовых данных

### 7. Фронтенд интеграция
- **Контроллер**: `app/Http/Controllers/CatalogController.php` - добавлена логика для отображения скидок
- **Контроллер**: `app/Http/Controllers/CartController.php` - добавлена логика для учета скидок в корзине
- **Шаблон**: `resources/views/catalog/show.blade.php` - отображение скидок на странице товара
- **Шаблон**: `resources/views/cart/index.blade.php` - отображение скидок в корзине

## Установка

### 1. Настройка базы данных
Убедитесь, что у вас настроен файл `.env` с параметрами подключения к базе данных:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 2. Запуск миграций
```bash
php artisan migrate
```

### 3. Запуск сидеров (опционально)
```bash
php artisan db:seed --class=PersonalDiscountSeeder
```

Или для всех сидеров:
```bash
php artisan db:seed
```

### 4. Очистка кэша (если раздел не появился)
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## Использование

### Админка
После настройки в админке MoonShine появится новый раздел "Персональные скидки" в группе "Каталог".

### Фронтенд
На странице товара для авторизованных пользователей с персональными скидками будет отображаться:
- ✅ Старая цена (зачеркнутая)
- ✅ Новая цена со скидкой (красным цветом)
- ✅ Процент скидки
- ✅ Описание скидки (если есть)

В корзине также отображается:
- ✅ Информация о скидках для каждого товара
- ✅ Общая экономия
- ✅ Итоговая сумма с учетом скидок

### Возможности:
- ✅ Создание персональных скидок для конкретных пользователей и товаров
- ✅ Установка процента скидки от 0 до 100%
- ✅ Включение/отключение скидок
- ✅ Добавление описания к скидкам
- ✅ Поиск по пользователям и товарам
- ✅ Предотвращение дублирования скидок
- ✅ Автоматическое отображение скидок на фронтенде
- ✅ Учет скидок в корзине

### Структура данных:
```sql
CREATE TABLE personal_discounts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    discount_percent DECIMAL(5,2) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_user_product (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

## Методы в модели Product

### 1. Получение персональной скидки пользователя:
```php
public function getPersonalDiscountForUser($userId)
{
    return $this->personalDiscounts()
        ->where('user_id', $userId)
        ->where('is_active', true)
        ->first();
}
```

### 2. Получение цены со скидкой:
```php
public function getDiscountedPrice($userId)
{
    $discount = $this->getPersonalDiscountForUser($userId);
    if ($discount) {
        return $this->price * (1 - $discount->discount_percent / 100);
    }
    return $this->price;
}
```

### 3. Проверка наличия скидки:
```php
public function hasPersonalDiscount($userId)
{
    return $this->personalDiscounts()
        ->where('user_id', $userId)
        ->where('is_active', true)
        ->exists();
}
```

## Тестирование

Для тестирования функционала:

1. **Создайте персональную скидку** в админке для конкретного пользователя и товара
2. **Авторизуйтесь** как этот пользователь
3. **Перейдите на страницу товара** - должна отобразиться скидка
4. **Добавьте товар в корзину** - скидка должна учитываться
5. **Проверьте корзину** - должна показать экономию и итоговую сумму со скидкой

## Примеры использования

### Создание скидки через админку:
1. Войдите в админку MoonShine
2. Перейдите в раздел "Персональные скидки"
3. Нажмите "Создать"
4. Выберите пользователя и товар
5. Установите процент скидки (например, 15%)
6. Добавьте описание (опционально)
7. Сохраните

### Результат на фронтенде:
- На странице товара: старая цена зачеркнута, новая цена красным
- В корзине: показана экономия и итоговая сумма со скидкой
- Только для авторизованного пользователя с персональной скидкой 