<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'customer_id' => factory(App\Customer::class),
        'product_id' => factory(App\Product::class),
        'quantity' => $faker->numberBetween(1, 10),
        'total_price' => $faker->randomFloat(2, 10, 200),
        'status' => $faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
        'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
        'updated_at' => $faker->dateTimeBetween('-1 month', 'now'),
    ];
});
