<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Payable;
use App\Models\Receivable;
use Illuminate\Database\Seeder;

class BusinessDataSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Customers
        |--------------------------------------------------------------------------
        */

        $aliTraders = Customer::create([
            'name' => 'Ali Traders',
            'email' => 'ali@traders.com',
            'phone' => '03001234567',
            'company' => 'Ali Traders Pvt Ltd',
        ]);

        $abcStore = Customer::create([
            'name' => 'ABC Store',
            'email' => 'abc@store.com',
            'phone' => '03011234567',
            'company' => 'ABC Store',
        ]);

        $xyzLtd = Customer::create([
            'name' => 'XYZ Ltd',
            'email' => 'info@xyz.com',
            'phone' => '03211234567',
            'company' => 'XYZ Limited',
        ]);

        $globalMart = Customer::create([
            'name' => 'Global Mart',
            'email' => 'global@mart.com',
            'phone' => '03331234567',
            'company' => 'Global Mart',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Receivables
        |--------------------------------------------------------------------------
        */

        Receivable::create([
            'customer_id' => $aliTraders->id,
            'amount' => 50000,
            'description' => 'Invoice #INV-1001',
            'due_date' => now()->addDays(10),
            'status' => 'pending',
        ]);

        Receivable::create([
            'customer_id' => $abcStore->id,
            'amount' => 30000,
            'description' => 'Invoice #INV-1002',
            'due_date' => now()->addDays(5),
            'status' => 'pending',
        ]);

        Receivable::create([
            'customer_id' => $xyzLtd->id,
            'amount' => 20000,
            'description' => 'Invoice #INV-1003',
            'due_date' => now()->subDays(5),
            'status' => 'overdue',
        ]);

        Receivable::create([
            'customer_id' => $globalMart->id,
            'amount' => 15000,
            'description' => 'Invoice #INV-1004',
            'due_date' => now()->addDays(15),
            'status' => 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Payables
        |--------------------------------------------------------------------------
        */

        Payable::create([
            'supplier_name' => 'ABC Suppliers',
            'amount' => 25000,
            'description' => 'Supplier Invoice #SUP-2001',
            'due_date' => now()->addDays(7),
            'status' => 'pending',
        ]);

        Payable::create([
            'supplier_name' => 'XYZ Wholesale',
            'amount' => 15000,
            'description' => 'Supplier Invoice #SUP-2002',
            'due_date' => now()->subDays(3),
            'status' => 'overdue',
        ]);

        Payable::create([
            'supplier_name' => 'Global Supplies',
            'amount' => 10000,
            'description' => 'Supplier Invoice #SUP-2003',
            'due_date' => now()->addDays(20),
            'status' => 'pending',
        ]);
    }
}
