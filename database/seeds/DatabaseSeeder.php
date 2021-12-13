<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if(\App\Role::all()->count() === 0) {
            App\Role::create(['name'=>'admin']);
            App\Role::create(['name'=>'agent']);
            App\Role::create(['name'=>'assistant']);
        }

        $this->call([
            UsersTableSeeder::class
        ]);

    }
}
