<?php

use Illuminate\Database\Seeder;

class AddDarkLogoToClientTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("ALTER TABLE royodelivery_db.`clients` ADD `dark_logo` VARCHAR(255) NULL AFTER `batch_allocation`");
    }
}
