<?php

use Illuminate\Database\Seeder;
use App\Model\Cms;

class CmsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $option_count = DB::table('cms')->count();
 
        $options = array(
            array(
                'id' => 1,
                'name' => 'Terms and Conditions',
                'content'=>'Terms and conditions allow you to establish what constitutes appropriate activity on your site or app, so you can remove abusive users and content that violates your guidelines.
                Intellectual property theft — Asserting your claim to the creative assets of your site in your terms and conditions will prevent ownership disputes and copyright infringement.
                Potential litigation — If a user lodges a legal complaint against your business, showing that they were presented with clear terms and conditions before they used your site will help you immensely in court.',
                'status' => 1
            ),
            array(
                'id' => 2,
                'name' => 'Privacy Policy',
                'content'=>'Not everyone knows how to make a Privacy Policy agreement, especially with CCPA or GDPR or CalOPPA or PIPEDA or Australia s Privacy Act provisions. If you are not a lawyer or someone who is familiar to Privacy Policies, you will be clueless. Some people might even take advantage of you because of this. Some people may even extort money from you. These are some examples that we want to stop from happening to you.
                We will help you protect yourself by generating a Privacy Policy. 
                Our Privacy Policy Generator can help you make sure that your business complies with the law. We are here to help you protect your business, yourself and your customers.
                Fill in the blank spaces below and we will create a personalized website Privacy Policy for your business. No account registration required. Simply generate & download a Privacy Policy in seconds! 
                Small remark when filling in this Privacy Policy generator: Not all parts of this Privacy Policy might be applicable to your website. When there are parts that are not applicable, these can be removed. Optional elements can be selected in step 2. The accuracy of the generated Privacy Policy on this website is not legally binding. Use at your own risk.',
                'status' => 1
            )
            
        );
        if($option_count == 0)
        {
          DB::statement('SET FOREIGN_KEY_CHECKS=0;');
          DB::table('cms')->truncate();
          DB::statement('SET FOREIGN_KEY_CHECKS=1;');
  
          DB::table('cms')->insert($options);
        }
        else{
            foreach ($options as $option) {
                $ops = Cms::where('name', $option['name'])->first();
   
                if ($ops !== null) {
                    // $ops->update(['name' => $option['name']]);
                } else {
                    $ops = Cms::create([
                      'id' => $option['id'],
                      'name' => $option['name'],
                      'content' => $option['content'],
                      'status' => $option['status'],
                    ]);
                }
            }
        }
    }
}
