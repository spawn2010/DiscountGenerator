<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
         \App\Models\User::factory()->create([
             'login' => 'Test User',
             'email' => 'test@example.com',
             'password' => Hash::make('password')
         ]);

         \App\Models\Discount::factory(100)->create();

    }
}
