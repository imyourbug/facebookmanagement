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
                    'email' => 'khachhang1@gmail.com',
                    'password' => Hash::make(1),
                    'role' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'email' => 'nhanvien1@gmail.com',
                    'password' => Hash::make(1),
                    'role' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'email' => 'nhanvien2@gmail.com',
                    'password' => Hash::make(1),
                    'role' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );

        Customer::create([
            'name' =>  'Khách hàng 1',
            'user_id' => 2
        ]);

        InfoUser::insert(
            [
                [
                    'name' => 'Nhân viên 1',
                    'user_id' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Nhân viên 2',
                    'user_id' => 4,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );



        Type::insert([
            [
                'name' => 'Kiểm soát côn trùng và dịch hại',
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vệ sinh công nghiệp',
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chăm sóc và duy tu cảnh quan',
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Diệt chuột',
                'parent_id' => 1, 'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Diệt côn trùng',
                'parent_id' => 1, 'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Diệt mối',
                'parent_id' => 1, 'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tưới cây',
                'parent_id' => 2, 'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bón phân',
                'parent_id' => 2, 'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Phun thuốc',
                'parent_id' => 2, 'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tẩy hóa chất',
                'parent_id' => 3, 'created_at' => now(),
                'updated_at' => now(),
            ],
            // [
            //     'name' => 'Giặt',
            //     'parent_id' => 3, 'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'name' => 'Thu gom rác',
            //     'parent_id' => 3, 'created_at' => now(),
            //     'updated_at' => now(),
            // ]
        ]);

        Setting::create([
            'key' => 'map',
            'value' => '',
        ]);

        //
        Branch::insert([
            [
                'name' => 'Chi nhánh Gia Lâm',
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chi nhánh Long Biên',
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chi nhánh Hoàng Mai',
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        Map::insert([
            [
                'area' => 'A',
                'position' => 'Cửa ra vào',
                'target' => 'Ruồi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'area' => 'A',
                'position' => 'Cửa ra vào',
                'target' => 'Muỗi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Chemistry::insert([
            [
                'code' => 'HC01',
                'name' => 'Hóa chất 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'HC02',
                'name' => 'Hóa chất 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Item::insert([
            [
                'name' => 'Vật tư 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vật tư 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Solution::insert([
            [
                'name' => 'Phương pháp 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Phương pháp 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
