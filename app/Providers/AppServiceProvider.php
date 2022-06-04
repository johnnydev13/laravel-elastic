<?php

namespace App\Providers;

use App\Interfaces\PostRepositoryInterface;
use App\Interfaces\TodoRepositoryInterface;
use App\Repositories\PostRepository;
use App\Repositories\TodoRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PostRepositoryInterface::class, function () {
            return new PostRepository(
                $this->app->make(Client::class)
            );
        });

        $this->app->bind(TodoRepositoryInterface::class, function () {
            return new TodoRepository(
                $this->app->make(Client::class)
            );
        });

        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('services.search.hosts'))
                ->build();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Since, we are always making sure to validate the user input, we can allow mass-assignment on all models
         */
        Model::unguard();
    }
}
