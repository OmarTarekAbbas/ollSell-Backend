<?php

namespace Modules\CoreData\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CoreDataDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
