<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Model\AgentSmsTemplate;
use Illuminate\Support\Str;

class AgentSMSTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $option_count = DB::table('agent_sms_templates')->count();

        $options = array(
            [
                'slug' => 'sign-up',
                'tags' => '{OTP}',
                'label' =>'Sign Up',
                'content' => 'Your Dispatcher verification code is {OTP}',
                
            ],
            [
                'slug' => 'sign-in',
                'tags' => '{OTP}',
                'label' => 'Sign In',
                'content' => 'Your Dispatcher verification code is {OTP}',
            ],
            [
              'slug' => 'driver-accepted',
              'tags' => '',
              'label' => getAgentNomenclature().' Accepted',
              'content' => '',
            ],
            [
              'slug' => 'driver-rejected',
              'tags' => '',
              'label' => getAgentNomenclature().' Rejected',
              'content' => '',
            ]
        );
        
        if($option_count == 0)
      {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('agent_sms_templates')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('agent_sms_templates')->insert($options);
      }
      else{
          foreach ($options as $option) {
              $ops = AgentSmsTemplate::where('slug', $option['slug'])->first();
 
              if ($ops !== null) {
                //   $ops->update(['content' => $option['content']]);
              } else {
                  $ops = AgentSmsTemplate::create([
                    'slug' => $option['slug'],
                    'tags' => $option['tags'],
                    'label' => $option['label'],
                    'content' => $option['content']
                  ]);
              }
          }
      }
    }
}
