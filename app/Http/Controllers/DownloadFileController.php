<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DownloadFileController extends Controller
{
    public function index(Request $request, $domain = '', $file_name){

		$file_path = public_path($file_name);
    	$headers = ['Content-Type: application/csv'];
    	return response()->download($file_path, $file_name, $headers);
    }

	public function downloadUploadedFile(Request $request, $domain = '', $file_name){

		$file_path = \Storage::disk('public')->get('routes/'.$file_name);
		   
		return (new Response($file_path, 200))
              ->header('Content-Type', 'application/csv');
    }
}
