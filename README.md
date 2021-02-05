## Installation

Clone the repo:

```shell
git clone git@github.com:muamarhm/testing-laravel.git
```

Install composer packages:

```shell
composer update
```

Copy and rename .env.example to .env, update the environmental variables and set an app key:

```shell
php artisan key:generate
```

After that, run all migrations and seed the database:

```shell
php artisan migrate
```

```shell
php artisan db:seed
```

Or if your database is fresh and you haven't done any work yet, then it's safe to call the commands in a single line:

```shell
php artisan migrate:refresh --seed
```

Note that seeding the database is compulsory as it will create the necessary roles and permissions for the user CRUD provided by the project.

Visit <div style="display: inline">http://yoursite.com/login</div> to sign in using below credentials:

#### Demo Admin Login

-   Email: admin@example.com
-   Password: 1234

### Credits:

-   [Laravel](https://github.com/laravel/laravel)
-   [Spatie Laravel Roles and Permissions](https://github.com/spatie/laravel-permission)
-   [vue-ios-alertview](https://github.com/Wyntau/vue-ios-alertview)
