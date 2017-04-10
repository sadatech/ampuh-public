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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'role' => 'arina',
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Store::class, function (Faker\Generator $faker) {

    return [
        'store_no' => $faker->buildingNumber(),
        'customer_id' => $faker->bankAccountNumber(),
        'store_name_1' => $faker->words(3, true),
        'store_name_2' => $faker->words(3, true),
        'channel' => $faker->randomElement(['Dept Store', 'Drug Store', 'Mensa', 'GT/MTI', 'MTKA Hyper/Super']),
        'account_id' => 1,
        'city_id' => 1,
        'region_id' => 1,
        'shipping_id' => 0,
        'alokasi_ba_nyx' => $faker->numberBetween(0,5),
        'alokasi_ba_oap' => $faker->numberBetween(0,5),
        'alokasi_ba_myb' => $faker->numberBetween(0,5),
        'alokasi_ba_gar' => $faker->numberBetween(0,5),
        'alokasi_ba_cons' => $faker->numberBetween(0,5),
        'reo_id' =>1
    ];
});


$factory->define(App\Ba::class, function (Faker\Generator $faker) {
    return [
        'nik' => $faker->buildingNumber,
        'name' => $faker->name,
        'no_ktp' => '1',
        'no_hp' => '2121',
        'brand_id' => '1',
        'rekening' => $faker->bankAccountNumber,
        'bank_name' => 'BNI',
        'status' => 'mobile',
        'join_date' => $faker->date(),
        'birth_date' => $faker->date(),
        'agency_id' => '1',
        'uniform_size' => 'S',
        'total_uniform' => '1',
        'description' => $faker->paragraphs(1, true),
        'class' => '1',
        'gender' => 'male',
        'approval_id' => '0',
        'approval_reason' => ' ',
        'division_id' => '1',
        'professional_id' => '1',
        'position_id' => '1',
        'foto_ktp' => 'a',
        'foto_tabungan' => 'a',
        'city_id' => '1',
        'area_id' => '1'
    ];
});

$factory->define(App\BaStore::class, function (Faker\Generator $faker) {
    return [
        'ba_id' => rand(42, 57),
        'store_id' => rand(1,100)
    ];
});


$factory->define(App\BaHistory::class, function (Faker\Generator $faker) {
    return [
        'ba_id' => 44,
        'store_id' => $faker->randomElement([110, 111, 135, 136, 137]),
        'status' => $faker->randomElement(['new', 'resign', 'rolling_in', 'rolling_out', 'cuti', 'rejoin']),
    ];
});