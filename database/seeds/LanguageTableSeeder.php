<?php

use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->delete();

        $language = [ 
                       ['name'=>'English','sort_code'=>'en'],
                       ['name'=>'Hindi','sort_code'=>'hi'],
                       ['name'=>'Italian','sort_code'=>'it'],
                       ['name'=>'French','sort_code'=>'fr']
                       
                    ];
        DB::table('languages')->insert($language);
    }
}
