<?php

use Illuminate\Database\Seeder;

class createTaskProof extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('task_proofs')->delete();
 
        $type = array(

            array(
                'id'                  => 1,
                'image'               => 1,
                'image_requried'      => 0,
                'signature'           => 0,
                'signature_requried'  => 0,
                'note'                => 0,
                'note_requried'       => 0,
                'barcode'             => 0,
                'barcode_requried'    => 0,
                'face'             => 0,
                'face_requried'    => 0,
                'type'                => 1
            ),
            array(
                'id'                  => 2,
                'image'               => 0,
                'image_requried'      => 0,
                'signature'           => 1,
                'signature_requried'  => 0,
                'note'                => 0,
                'note_requried'       => 0,
                'barcode'             => 0,
                'barcode_requried'    => 0,
                'face'             => 0,
                'face_requried'    => 0,
                'type'                => 2
            ),
            array(
                'id'                  => 3,
                'image'               => 0,
                'image_requried'      => 0,
                'signature'           => 1,
                'signature_requried'  => 0,
                'note'                => 0,
                'note_requried'       => 0,
                'barcode'             => 0,
                'barcode_requried'    => 0,
                'face'             => 0,
                'face_requried'    => 0,
                'type'                => 3
            )

        );
        
        DB::table('task_proofs')->insert($type);
    }
}
