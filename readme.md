# Server Base - Challenge Backend Laravel


## Envinroment setup

1) Create database
2) Copy .env.example to .env and fill with database credentials.


3) Migrations:
``` bash
php artisan migrate
```

4) Seeders:
``` bash
npx sequelize-cli db:seed:all
use php artisan jwt:generate to generate a key
```

## Start local server

``` bash
php artisan serve
```