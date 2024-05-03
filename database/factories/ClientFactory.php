<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Client;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Client::class, function (Faker $faker) {

    $code = substr(md5(microtime()), 0, 6);
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone_number' => $faker->unique()->phoneNumber,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'database_path' => $faker->url,
        'database_name' => $faker->userName,
        'database_username' => $faker->userName,
        'database_password' => $faker->password,
        'company_name' => $faker->company,
        'company_address' => $faker->address,
        'custom_domain' => $faker->domainName,
        'code' => $code,

    ];
});