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
        // This seeder is designed to be run with a specific user ID
        // Usage: php artisan db:seed --class=DatabaseSeeder --user=1
        // Or create records for a specific user programmatically
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
