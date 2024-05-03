<?php

use Illuminate\Database\Seeder;
use App\Model\NotificationType;
use App\Model\NotificationEvent;
class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notification_events')->delete();
        DB::table('notification_types')->delete();

        $notification_types = NotificationType::create([
            'name' => 'Pickup Notifications'
        ]);

        // create all the events in the Pickup Notifications //
        $notification_types->notification_events()->createMany([
            ['name' => 'Agent Started','message'=>'Driver "driver_name" in our "vehicle_model" with license plate "plate_number" is heading to your location. You can track them here "tracking_link" '],
            ['name' => 'Agent Arrived','message'=>'Driver "driver_name" in our "vehicle_model" with license plate "plate_number" has arrived at your location.'],
            ['name' => 'Successful','message'=>'Thank you, your order ("order_number") has been delivered successfully by driver "driver_name" You can rate them here ."feedback_url"'],
            ['name' => 'Failed','message'=>'Sorry, our driver "driver_name" is not able to complete your order ("order_number") delivery']
        ]);

        $notification_types_d = NotificationType::create([
            'name' => 'Drop-off Notifications'
        ]);

        // create all the events in the drop-off Notifications //
        $notification_types_d->notification_events()->createMany([
            ['name' => 'Agent Started','message'=>'Driver "driver_name" in our "vehicle_model" with license plate "plate_number" is heading to your location. You can track them here "tracking_link" '],
            ['name' => 'Agent Arrived','message'=>'Driver "driver_name" in our "vehicle_model" with license plate "plate_number" has arrived at your location.'],
            ['name' => 'Successful','message'=>'Thank you, your order has been delivered successfully by driver "driver_name" You can rate them here ."feedback_url"'],
            ['name' => 'Failed','message'=>'Sorry, our driver "driver_name" is not able to complete your order delivery']
        ]);

        $notification_types_d = NotificationType::create([
            'name' => 'Appointment Notifications'
        ]);

        // create all the events in the drop-off Notifications //
        $notification_types_d->notification_events()->createMany([
            ['name' => 'Agent Started','message'=>'Driver "driver_name" in our "vehicle_model" with license plate "plate_number" is heading to your location. You can track them here "tracking_link" '],
            ['name' => 'Agent Arrived','message'=>'Driver "driver_name" in our "vehicle_model" with license plate "plate_number" has arrived at your location.'],
            ['name' => 'Successful','message'=>'Thank you, your order has been delivered successfully by driver "driver_name" You can rate them here ."feedback_url"'],
            ['name' => 'Failed','message'=>'Sorry, our driver "driver_name" is not able to complete your order delivery']
        ]);

        $notification_types_d = NotificationType::create([
            'name' => 'Customer Delivery OTP'
        ]);

        $notification_types_d->notification_events()->createMany([
            ['name' => 'Delivery OTP','message'=>'We have delivered your order number "order_number" please provide your OTP "deliver_otp" to your agent to order delivery'],
        ]);

        $notification_types_d = NotificationType::create([
            'name' => 'Pickup Deliver Driver Notifications'
        ]);

        $notification_types_d->notification_events()->createMany([
            ['name' => 'Successful','message'=>'Thank you, your ride has been successfully completed by "driver_name" You can rate them here ."feedback_url"']
        ]);
    }
}
