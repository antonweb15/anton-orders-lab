<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersTableSeeder extends Seeder
{
    public function run(): void
    {
        Order::truncate(); // Clear the table before filling

        Order::factory()->count(50)->create();
    }
}
