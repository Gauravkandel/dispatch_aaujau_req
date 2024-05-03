<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderCancelReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_cancel_reasons')->delete();
 
        $type = array(
            array(
                'id' => 1,
                'reason' => 'Unable to contact customer',
                'status' => '1',
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 2,
                'reason' => 'The merchant was closed',
                'status' => '1',
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 3,
                'reason' => 'Customer rejected the order due to missing or incorrect products',
                'status' => '1',
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 4,
                'reason' => 'Customer rejected the order because it arrived in poor condition',
                'status' => '1',
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 5,
                'reason' => 'Customer rejected the order due to late delivery',
                'status' => '1',
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 6,
                'reason' => 'Customer refused to pay for order',
                'status' => '1',
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 7,
                'reason' => 'Customer left location',
                'status' => '1',
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            )
        );
        DB::table('order_cancel_reasons')->insert($type);
    }
}
