<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $post = Post::all();
        $user = User::all();
        return [
            'post_id' => fake()->randomElement($post)->id,
            'user_id' => fake()->randomElement($user)->id,
            'comment' => fake()->text(),
        ];
    }
}
