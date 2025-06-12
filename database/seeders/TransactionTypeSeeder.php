<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    public function run(): void
    {
        TransactionType::create([
            'name' => 'Kas',
            'code' => 'KAS',
            'description' => 'Transaksi Kas',
            'is_active' => true,
        ]);

        TransactionType::create([
            'name' => 'Kebersihan',
            'code' => 'KEBERSIHAN',
            'description' => 'Transaksi Kebersihan',
            'is_active' => true,
        ]);
    }
} 