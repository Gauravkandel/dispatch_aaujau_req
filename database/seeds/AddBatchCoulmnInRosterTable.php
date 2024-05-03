<?php

use Illuminate\Database\Seeder;

class AddBatchCoulmnInRosterTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("ALTER TABLE royodelivery_db.`rosters` ADD `batch_no` VARCHAR(255) NULL AFTER `notification_befor_time`");
    }
}
