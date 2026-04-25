<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $images = [
            'book1.jpg',
            'book2.jpg',
            'book3.jpg',
            'book4.jpg',
            'book5.jpg'
        ];

        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'price' => rand(50000, 200000),
            'image' => $images[array_rand($images)],
            'description' => $this->faker->paragraph(),
        ];
    }
}
