## How to run
- copy `.env.example` to `.env`
- change these database configuration ` DB_HOST=35.229.131.72 DB_PORT=3306 DB_DATABASE=webapp DB_USERNAME=server DB_PASSWORD=1234`
- run `php artisan migrate`
- run `php artisan passport:install`
- run `php artisan serve` to open the server
