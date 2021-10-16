<?php

namespace Database\Seeders;

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
         \App\Models\User::factory(10)->create();
         \App\Models\Blog::factory(100)->create();
         \App\Models\Website::factory(10)->create();
         \App\Models\Subscriber::factory(10)->create();
    }
}
