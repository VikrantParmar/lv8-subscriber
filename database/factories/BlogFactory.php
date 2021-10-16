<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Blog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence;

        /*$post = collect($this->faker->paragraphs(rand(5, 15)))
            ->map(function($item){
                return "<p>$item</p>";
            })->toArray();

        $post = implode($post);*/

        return [
            'title' => $title,
            'website_id' => rand(1,10),
            'description' => $this->faker->paragraph,
            'slug' => Str::slug($title)
        ];

    }
}
