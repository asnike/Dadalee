<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $faker = Faker::create();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Model::unguard();
        App\User::truncate();
        factory(App\User::class, 10)->create();
        $this->command->info('users table seeded');

        App\RealEstate::truncate();
        factory(App\RealEstate::class, 10)->create();
        $this->command->info('realestates table seeded');

        Model::reguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
