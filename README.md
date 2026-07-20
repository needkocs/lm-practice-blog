# Блог

## Стек

- PHP 8.4
- Laravel 13
- Inertia.js 3
- Vue 3
- Tailwind CSS 4
- Vite 8
- SQLite

## Требования

- PHP 8.4 с расширениями, необходимыми Laravel
- Composer
- Node.js и npm
- SQLite

## Локальный запуск

1. Установка зависимостей

    ```bash
    composer install
    npm install
    ```

2. Быстрое развертывание приложения

   ```bash
   composer run setup
   ```

3. Сгенерировать тестовые данные

    ```bash
    php artisan migrate --seed
    ```
   
4. Запустить локальный сервер, будет доступно по адресу: http://127.0.0.1:8000/

    ```bash
    composer run dev
    ```


## Тестовый пользователь

* Login: demo@example.com
* Password: password