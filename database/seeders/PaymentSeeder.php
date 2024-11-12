<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payment::insert([
            ['user_id' => 96, 'status' => 'paid', 'amount' => 1000.00, 'due_date' => '2024-08-11', 'late_fee' => null],
            ['user_id' => 97, 'status' => 'unpaid', 'amount' => 0.00, 'due_date' => '2024-10-06', 'late_fee' => null],
            ['user_id' => 98, 'status' => 'unpaid', 'amount' => 0.00, 'due_date' => '2024-08-17', 'late_fee' => null],
            ['user_id' => 99, 'status' => 'paid', 'amount' => 100.00, 'due_date' => '2024-08-17', 'late_fee' => null],
            ['user_id' => 102, 'status' => 'unpaid', 'amount' => 0.00, 'due_date' => '2024-09-26', 'late_fee' => null],
            ['user_id' => 109, 'status' => 'unpaid', 'amount' => 0.00, 'due_date' => '0345-06-04', 'late_fee' => null],
            ['user_id' => 110, 'status' => 'paid', 'amount' => 2.00, 'due_date' => '2024-02-07', 'late_fee' => null],
            ['user_id' => 111, 'status' => 'paid', 'amount' => 0.00, 'due_date' => '2024-02-01', 'late_fee' => null],
            ['user_id' => 112, 'status' => 'paid', 'amount' => 900.00, 'due_date' => '2024-04-22', 'late_fee' => null],
            ['user_id' => 96, 'status' => 'paid', 'amount' => 0.00, 'due_date' => '2024-09-11', 'late_fee' => null],
            // Add remaining records here
        ]);
    }
}
