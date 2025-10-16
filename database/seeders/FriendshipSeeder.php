<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Friendship;

class FriendshipSeeder extends Seeder
{
    public function run(): void
    {
        $fromIds = [2, 3, 4, 5, 6];
        $toId = 1;

        foreach ($fromIds as $fromId) {
            Friendship::factory()->fromTo($fromId, $toId)->create();
        }
    }
}
