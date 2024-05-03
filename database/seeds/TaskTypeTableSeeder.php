<?php

use Illuminate\Database\Seeder;

class TaskTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('task_types')->delete();
 
        $type = array(
            array(
                'id' => 1,
                'name' => 'Pickup'
            ),
            array(
                'id' => 2,
                'name' => 'Drop-off'
            ),
            array(
                'id' => 3,
                'name' => 'Appointment'
            )
        );
        DB::table('task_types')->insert($type);
    }
}
