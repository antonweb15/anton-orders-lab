<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    public function run(): void
    {
        Order::truncate(); // Clear the table before filling

        Order::factory()->count(50)->create();
    }
}
