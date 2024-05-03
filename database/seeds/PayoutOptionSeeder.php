<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Model\PayoutOption;
class PayoutOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){ 

      $option_count = DB::table('payout_options')->count();

      $payout_options = array(
        array('id' => '1', 'path' => '', 'code' => 'cash', 'title' => 'Off the Platform', 'off_site' => '0', 'status' => '0'),
        array('id' => '2', 'path' => 'omnipay/stripe', 'code' => 'stripe', 'title' => 'Stripe', 'off_site' => '0', 'status' => '0'),
        array('id' => '3', 'path' => 'razorpay/razorpay', 'code' => 'razorpay', 'title' => 'Razorpay', 'off_site' => '0', 'status' => '0'),
        array('id' => '4', 'path' => '', 'code' => 'bank_account_m_india', 'title' => 'Bank Account', 'off_site' => '0', 'status' => '0')
      ); 

      if($option_count == 0)
      {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('payout_options')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('payout_options')->insert($payout_options);
      }
      else{
          foreach ($payout_options as $option) {
              $payop = PayoutOption::where('code', $option['code'])->first();
 
              if ($payop !== null) {
                  $payop->update(['title' => $option['title'],'off_site' => $option['off_site']]);
              } else {
                  $payop = PayoutOption::create([
                    'title' => $option['title'],
                    'code' => $option['code'],
                    'path' => $option['path'],
                    'off_site' => $option['off_site'],
                    'status' => $option['status'],
                  ]);
              }
          }
      }
     
     
      
    }
}