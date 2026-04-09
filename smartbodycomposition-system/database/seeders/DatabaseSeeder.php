<?php

namespace Database\Seeders;

use App\Models\BodyComposition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Generate 30 days of body composition data for user ID 3
        $this->seedBodyCompositionsForUser(3, 30);
    }

    /**
     * Create body composition records for a specific user.
     * Usage in routes or commands:
     *
     * app(DatabaseSeeder::class)->seedBodyCompositionsForUser($userId, $count);
     */
    public function seedBodyCompositionsForUser($userId, $count = 10)
    {
        BodyComposition::factory()
            ->count($count)
            ->forUser(\App\Models\User::find($userId))
            ->create();
    }
}
