<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $users = User::all()->shuffle();

        $user = $users->pop();

        return [
            'sender_id' => $user,
            'receiver_id' => $users->random()->id,
            'text' => fake()->text(),
            'created_at' => fake()->dateTimeBetween('-2 year', 'now')
        ];
    }
}
