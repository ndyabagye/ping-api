<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Check;
use App\Models\Service;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Henry Ndyabagye',
            'email' => 'henryn@test.com',
        ]);

        $service = Service::factory()->for($user)->create([
            'name' => 'Trebble API',
            'url' => 'https://api.trebble.com',
        ]);

        Check::factory()->for($service)->count(10)->create();
    }
}
