<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
//chèn thêm thư viện User của folder models sau khi nó đã được nhận phương thức factory bằng @extend từ file UserFactory để tiến hành fake user
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(500)->create();
    }
}
