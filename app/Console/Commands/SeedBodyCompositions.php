<?php

namespace App\Console\Commands;

use App\Models\BodyComposition;
use App\Models\User;
use Illuminate\Console\Command;

class SeedBodyCompositions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:body-compositions {--user-id= : The user ID to seed data for} {--count=10 : Number of records to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed body composition records for a specific user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        $count = (int) $this->option('count');

        if (!$userId) {
            $this->error('Please provide a user ID: --user-id=1');
            return 1;
        }

        $user = User::find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }

        $this->info("Creating {$count} body composition records for user: {$user->name}");

        BodyComposition::factory()
            ->count($count)
            ->forUser($user)
            ->create();

        $this->info("Successfully created {$count} body composition records!");
        return 0;
    }
}
