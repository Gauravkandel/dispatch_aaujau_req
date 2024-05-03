<?php

use Illuminate\Database\Seeder;

class AddColumnToMailDB extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("ALTER TABLE `rosters` ADD COLUMN  `notification_befor_time` DATETIME NULL ");
    }
}
