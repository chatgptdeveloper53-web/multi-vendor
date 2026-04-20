<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'nom' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('adminadmin')
        ]);

        // Create vendeur user
        User::factory()->create([
            'nom' => 'Vendeur User',
            'email' => 'vendeur@example.com',
            'role' => 'vendeur',
            'password' => Hash::make('adminadmin')
        ]);

        // Create acheteur user
        User::factory()->create([
            'nom' => 'Acheteur User',
            'email' => 'acheteur@example.com',
            'role' => 'acheteur',
            'password' => Hash::make('adminadmin'),
        ]);
    }
}
