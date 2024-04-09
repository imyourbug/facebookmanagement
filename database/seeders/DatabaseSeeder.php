<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Branch;
use App\Models\Chemistry;
use App\Models\Customer;
use App\Models\InfoUser;
use App\Models\Item;
use App\Models\Map;
use App\Models\Setting;
use App\Models\Solution;
use App\Models\TaskType;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $this->call([
        // ]);

        User::insert(
            [
                [
                    'email' => 'duongvankhai2022001@gmail.com',
                    'password' => Hash::make(1),
                    'role' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'email' => 'user1@gmail.com',
                    'password' => Hash::make(1),
                    'role' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'email' => 'user2@gmail.com',
                    'password' => Hash::make(1),
                    'role' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );

        Setting::create([
            'key' => 'map',
            'value' => '',
        ]);
    }
}
