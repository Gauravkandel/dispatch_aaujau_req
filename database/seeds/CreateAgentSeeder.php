<?php

use Illuminate\Database\Seeder;

class CreateAgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('agents')->delete();
 
        $type = array(
            array(
                'id'               => 1,
                'team_id'          => 1,
                'name'             => 'Michael Schumacher',
                'profile_picture'  => 'assets/client_00000051/agents5fedb209f1eea.jpeg/Ec9WxFN1qAgIGdU2lCcatJN5F8UuFMyQvvb4Byar.jpg',
                'type'             => 'Employee',
                'vehicle_type_id'  => 4,
                'make_model'       => '2021',
                'plate_number'     => 'pb-yb-2000',
                'phone_number'     => '9638527410',
                'color'            => 'red',
            ),
            array(
                'id'               => 2,
                'team_id'          => 2,
                'name'             => 'Sebastian Vettel',
                'profile_picture'  => 'assets/client_00000051/agents5fedb446c1e33.jpg/cadu0HOMN8xyQc8NAuMhao5SNWgZgUohzrXwJqYA.jpg',
                'type'             => 'Employee',
                'vehicle_type_id'  => 3,
                'make_model'       => '2021',
                'plate_number'     => 'pb-yb-2021',
                'phone_number'     => '7418529630',
                'color'            => 'black',
            )
        );
        DB::table('agents')->insert($type);
    }
}
