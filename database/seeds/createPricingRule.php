<?php

use Illuminate\Database\Seeder;

class createPricingRule extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('price_rules')->delete();
 
        $type = array(
            array(
                'id'                               => 1,
                'name'                             => 'default',
                'start_date_time'                  => '2021-01-01 00:00:00',
                'end_date_time'                    => '2030-01-29 00:00:00',
                'is_default'                       => 1,
                'geo_id'                           => 1,
                // 'team_id'                          => ,
                // 'team_tag_id'                      => '',
                // 'driver_tag_id'                    => '',
                'base_price'                       => 10,
                'base_duration'                    => 10.00,
                'base_distance'                    => 10.00,
                'base_waiting'                     => 10,
                'duration_price'                   => 5.00,
                'waiting_price'                    => 8.00,
                'distance_fee'                     => 5.00,
                'cancel_fee'                       => 2.00,
                'agent_commission_percentage'      => 5,
                'agent_commission_fixed'           => 6,
                'freelancer_commission_percentage' => 5,
                'freelancer_commission_fixed'      => 4,
            )
        );
        DB::table('price_rules')->insert($type);
    }
}
