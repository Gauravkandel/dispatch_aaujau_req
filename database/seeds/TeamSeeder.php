<?php

use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vehicle_types')->delete();
 
        $type = array(
            array(
                'id' => 1,
                'name' => 'onfoot'
            ),
            array(
                'id' => 2,
                'name' => 'bycycle'
            ),
            array(
                'id' => 3,
                'name' => 'motorbike'
            ),
        );
        DB::table('vehicle_types')->insert($type);
    }
}