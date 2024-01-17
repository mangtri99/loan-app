<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            StatusSeeder::class,
            TypeSeeder::class,
        ]);

        \App\Models\User::factory()->create(
            [
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'role_id' => Role::ADMIN
            ]
        );
        \App\Models\User::factory(10)->create();

        $this->call([
            LoanSeeder::class,
            RepaymentSeeder::class,
        ]);
    }
}
