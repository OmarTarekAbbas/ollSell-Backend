<?php

namespace Modules\CoreData\Database\Factory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\CoreData\Entities\Category;

class CategoryFactory extends Factory
{
     /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'metaTitle' => fake()->name(),
            'metaDescription' => fake()->name(),
            'status' => 1,
        ];
    }
}
