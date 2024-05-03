<?php
namespace App\Traits;
use DB;
use Illuminate\Support\Collection;
use App\Model\{Client, ClientPreference, User, Agent, Order, PaymentOption, PayoutOption, AgentPayout};

trait googleMapApiFunctions{

    //------------------------------Function created by surendra singh--------------------------//
    public function GetTotalTime($lat1, $long1, $lat2, $long2)
    {
        $client = ClientPreference::where('id', 1)->first();
        $ch = curl_init();
        $headers = array('Accept: application/json',
                   'Content-Type: application/json',
                   );
        $url =  'https://maps.googleapis.com/maps/api/directions/json?origin='.$lat1.','.$long1.'&destination='.$lat2.','.$long2.'&key='.$client->map_key_1;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $result = json_decode($response);
        curl_close($ch); // Close the connection
        $routes = $result->routes[0]->legs[0]->steps;
        $time = $result->routes[0]->legs[0]->duration->value;
        $output = array();
        $output['total_time'] = $time;
        return $output;
    }
    //-------------------------------------------------------------------------------------------//
    

}
