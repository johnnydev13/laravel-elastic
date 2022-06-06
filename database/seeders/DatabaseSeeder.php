<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Todo;
use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(3)->create();
        Post::factory(5)->create();

        /**
         * Create Todo factories for `Pending` as well as `Completed` statuses
         */
        Todo::factory(5)->pending()->create();
        Todo::factory(5)->completed()->create();
    }
}
