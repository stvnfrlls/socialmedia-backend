<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Friend>
 */
class FriendFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::all()->shuffle();

        $user = $users->pop(); // Get and remove one user from the shuffled collection

        return [
            'user_id' => $user->id,
            'friend_id' => $users->random()->id,
            'accepted' => fake()->dateTime(),
        ];
    }
}
