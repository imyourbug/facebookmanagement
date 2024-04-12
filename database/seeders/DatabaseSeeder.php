<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Comment;
use App\Models\Setting;
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
                    'email' => 'user@gmail.com',
                    'password' => Hash::make(1),
                    'role' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );

        Setting::insert([
            [
                'key' => 'craw-count',
                'name' => 'Số luồng crawl count',
                'value' => '5',
            ],
            [
                'key' => 'delay-time',
                'name' => 'Delay time mỗi luồng crawl count (ms)',
                'value' => '2000',
            ]
        ]);

        Comment::create([
            'title' => 'title',
            'uid' => 'uid',
            'phone' => '0368822543',
            'content' => 'content',
            'note' => 'note',
        ]);
    }
}
