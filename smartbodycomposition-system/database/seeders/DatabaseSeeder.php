<?php

namespace Database\Seeders;

use App\Models\BodyComposition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Generate the full fixed 6-month body composition timeline for user ID 1.
        $startDate = Carbon::create(2025, 10, 10);
        $endDate = Carbon::create(2026, 4, 10);

        $this->seedBodyCompositionsForUser(1, $startDate->diffInDays($endDate) + 1);
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
