<?php

use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;

class AddCoulmnInClientTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // this seeder is for only main db to create new coulmn in client table for batch allocation
        DB::statement("ALTER TABLE royodelivery_db.`clients` ADD `batch_allocation` TINYINT NULL DEFAULT '0' AFTER `dial_code`");
    }
}
