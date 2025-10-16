<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Friendship;

class FriendshipFactory extends Factory
{
    protected $model = Friendship::class;

    public function definition(): array
    {
        return [
            'from_user_id' => 1,
            'to_user_id' => 1,
        ];
    }

    public function fromTo(int $from, int $to): self
    {
        return $this->state(function () use ($from, $to) {
            return [
                'from_user_id' => $from,
                'to_user_id' => $to,
            ];
        });
    }
}
