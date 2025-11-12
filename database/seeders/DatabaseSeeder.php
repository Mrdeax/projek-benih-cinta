<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Member;
use App\Models\MemberDiscount;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        MemberDiscount::create([
            'minimum_purchase' => 0,
            'discount_percentage' => 0,
            'description' => 'Tanpa Diskon'
        ]);

        MemberDiscount::create([
            'minimum_purchase' => 100000,
            'discount_percentage' => 5,
            'description' => 'Member Reguler'
        ]);

        MemberDiscount::create([
            'minimum_purchase' => 500000,
            'discount_percentage' => 10,
            'description' => 'Member Gold'
        ]);

        MemberDiscount::create([
            'minimum_purchase' => 1000000,
            'discount_percentage' => 15,
            'description' => 'Member Platinum'
        ]);

        $admin = User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@kasir.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1'
        ]);

        $officer = User::create([
            'name' => 'Petugas Kasir',
            'username' => 'petugas',
            'email' => 'petugas@kasir.local',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'phone' => '081234567891',
            'address' => 'Jl. Petugas No. 1'
        ]);

        $member1 = User::create([
            'name' => 'Budi Santoso',
            'username' => 'budi',
            'email' => 'budi@kasir.local',
            'password' => Hash::make('password'),
            'role' => 'member',
            'phone' => '081234567892',
            'address' => 'Jl. Raya No. 10'
        ]);

        $member2 = User::create([
            'name' => 'Siti Nurhaliza',
            'username' => 'siti',
            'email' => 'siti@kasir.local',
            'password' => Hash::make('password'),
            'role' => 'member',
            'phone' => '081234567893',
            'address' => 'Jl. Merdeka No. 20'
        ]);

        Member::create([
            'user_id' => $member1->id,
            'member_code' => 'MBR-' . date('Ymd') . '-00001',
            'join_date' => date('Y-m-d'),
            'total_purchase' => 0
        ]);

        Member::create([
            'user_id' => $member2->id,
            'member_code' => 'MBR-' . date('Ymd') . '-00002',
            'join_date' => date('Y-m-d'),
            'total_purchase' => 0
        ]);

        $products = [
            ['code' => 'PRD001', 'name' => 'Laptop Dell', 'price' => 8000000, 'stock' => 5, 'minimum_stock' => 2],
            ['code' => 'PRD002', 'name' => 'Mouse Logitech', 'price' => 250000, 'stock' => 20, 'minimum_stock' => 5],
            ['code' => 'PRD003', 'name' => 'Keyboard Mechanical', 'price' => 500000, 'stock' => 10, 'minimum_stock' => 3],
            ['code' => 'PRD004', 'name' => 'Monitor LG 24"', 'price' => 2000000, 'stock' => 8, 'minimum_stock' => 2],
            ['code' => 'PRD005', 'name' => 'Headphone Sony', 'price' => 1500000, 'stock' => 12, 'minimum_stock' => 4],
            ['code' => 'PRD006', 'name' => 'Webcam HD', 'price' => 400000, 'stock' => 15, 'minimum_stock' => 5],
            ['code' => 'PRD007', 'name' => 'Printer Canon', 'price' => 1200000, 'stock' => 6, 'minimum_stock' => 1],
            ['code' => 'PRD008', 'name' => 'Router WiFi', 'price' => 300000, 'stock' => 25, 'minimum_stock' => 5],
            ['code' => 'PRD009', 'name' => 'USB Hub', 'price' => 150000, 'stock' => 30, 'minimum_stock' => 10],
            ['code' => 'PRD010', 'name' => 'Card Reader', 'price' => 100000, 'stock' => 40, 'minimum_stock' => 10],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}