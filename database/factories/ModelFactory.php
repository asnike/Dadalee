<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\RealEstate::class, function(Faker\Generator $faker){
    return [
        'name'=>$faker->name,
        'address'=>$faker->sentence(5),
        'lat'=>rand(-90, 90),
        'lng'=>rand(-180, 180),
        'own'=>(bool)rand(0, 1),
        'user_id'=>1,
    ];
});

$factory->define(App\RepayMethod::class, function(Faker\Generator $faker){
    return [
        'name'=>$faker->name
    ];
});

$factory->define(App\PriceTag::class, function(Faker\Generator $faker){
    return [
        'sigungu'=>$faker->name,
        'lat'=>rand(-90, 90),
        'lng'=>rand(-180, 180),
        'user_id'=>1,
    ];
});


$factory->define(App\EarningRate::class, function(Faker\Generator $faker){
    return [

    ];
});
$factory->define(App\Loan::class, function(Faker\Generator $faker){
    return [

    ];
});