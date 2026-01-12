<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersTableSeeder extends Seeder
{
    public function run(): void
    {
        Order::truncate(); // очищаем таблицу перед заполнением

        Order::factory()->count(50)->create();
    }
}
