<?php

use Illuminate\Database\Seeder;

class CreateGeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        
        DB::table('driver_geos')->delete();
 
        $type = array(
            array(
                'id'               => 1,
                'geo_id'           => 1,
                'driver_id'        => 1,
            ),
            array(
                'id'               => 2,
                'geo_id'           => 1,
                'driver_id'        => 2,
            ),
            array(
                'id'               => 3,
                'geo_id'           => 2,
                'driver_id'        => 1,
            ),
            array(
                'id'               => 4,
                'geo_id'           => 2,
                'driver_id'        => 2,
            ),
            array(
                'id'               => 5,
                'geo_id'           => 3,
                'driver_id'        => 1,
            ),
            array(
                'id'               => 6,
                'geo_id'           => 3,
                'driver_id'        => 2,
            ),
        );
        DB::table('driver_geos')->insert($type);
    }
}
