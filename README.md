# Stack

-   PHP 8
-   Laravel 9
-   Mysql 8
-   Elastic Search 7

# Dev Requirements

-   Docker (latest)
-   Docker Compose (latest)
-   Make sure that ports (8000, 8002, 8025, 9200) are not used on your host machine cause they are used by the app docker-compose file

# Run

We are using [laravel/sail](https://laravel.com/docs/8.x/sail) for the development environment, just follow these steps to start the application.

-   `cp .env.example .env` then fill the required data
-   `docker-compose run --rm --user=$(id -u) npm install`
-   `docker-compose run --rm --user=$(id -u) composer install --ignore-platform-reqs`
-   `docker-compose run --rm --user=$(id -u) php artisan key:generate`
-   `./vendor/bin/sail up -d`
-   `./vendor/bin/sail artisan migrate:fresh --seed`
    **Note:** Elastic Search indexes automatically applies when the data is created/updated in DB and automatically removes when the data is deleted
    But in case you make some changes manually in the DB, then indexes dont apply so you need to run the following command to put Elastic Search indexes on all the models
-   `./vendor/bin/sail artisan search:reindex`
-   `./vendor/bin/sail artisan test`

# Utilities

The installation has many usefull utilities to speedup the development process.

-   application at [localhost:8000](http://localhost:8000)
-   phpmyadmin at [localhost:8002](http://localhost:8002)
-   elastic-search at [localhost:9200](http://localhost:9200)
