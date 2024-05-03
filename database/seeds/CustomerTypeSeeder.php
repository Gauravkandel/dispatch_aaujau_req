<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customer_type')->delete();
 
        $type = array(
            array(
                'id' => 1,
                'title' => 'Individual'
            ),
            array(
                'id' => 2,
                'title' => 'Retail Store'
            ),
            array(
                'id' => 3,
                'title' => 'Distribution Center'
            ),
        );
        DB::table('customer_type')->insert($type);
    }
}
