<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Acl\Entities\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::create([
            'name' => 'Suppler 1',
            'email' => 'supplier1@gmail.com', 
            'password' => Hash::make('12345678')
        ]);
        Supplier::create([
            'name' => 'Suppler 2',
            'email' => 'supplier2@gmail.com', 
            'password' => Hash::make('12345678')
        ]);
    }
}
