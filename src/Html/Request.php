<?php
 
namespace App\Html;
 
use App\RestApiClient\Client;
 
class Request {
 
    static function handle()
    {
        switch ($_SERVER["REQUEST_METHOD"]){
            case "POST":
                self::postRequest();
                break;
            case "GET";
            default:
                //self::getRequest();
                break;
        }
    }
 
    private static function postRequest()
{
    $request = $_REQUEST;
    $client = new Client();

    // Check if the relevant buttons are set in the request
    if (isset($request['btn-home'])) {
        // Handle home button action
    } elseif (isset($request['btn-counties'])) {
        PageCounties::table(self::getCounties());
    } elseif (isset($request['btn-del-county'])) {
        $client->delete('counties', $request['btn-del-county']);
        PageCounties::table(self::getCounties());
    } elseif (isset($request['btn-save-county'])) {
        $countyName = $request['name'];
        $client->post('counties', ['name' => $countyName]);
        PageCounties::table(self::getCounties());
    } elseif (isset($request['btn-edit-county'])) {
        $countyId = $request['btn-edit-county'];
        $county = $client->get("counties/$countyId")['data'];
        PageCounties::editForm($county);
    } elseif (isset($request['btn-update-county'])) {
        $countyId = $request['id'];
        $countyName = $request['name'];
        $client->put("counties/$countyId", ['name' => $countyName]);
        PageCounties::table(self::getCounties());
    } elseif (isset($request['btn-cancel'])) {
        PageCounties::table(self::getCounties());
    } if (isset($request['btn-cities'])) {
        PageCities::dropdown(self::getCounties());
    
        // Debugging: Log the request data
        error_log(print_r($request, true)); // This will log the entire request array
    
        if (isset($request['id_county'])) {
            $countyId = $request['id_county'];
            self::showCities($countyId);
        } else {
            error_log("Error: 'id_county' is not set in the request.");
        }
    } elseif (isset($request['btn-del-city'])) {
        
        $client->delete('cities', $request['btn-del-city']);
        PageCities::table(self::showCities($countyId));
    } elseif (isset($request['btn-save-city'])) {
        $cityName = $request['city'];
        $client->post('cities', ['city' => $cityName]);
        PageCities::table(self::getCounties());
    } elseif (isset($request['btn-edit-city'])) {
        $cityId = $request['btn-edit-city'];
        $city = $client->get("cities/$cityId")['data'];
        PageCities::editForm($city);
    } elseif (isset($request['btn-update-city'])) {
        $cityId = $request['id'];
        $cityName = $request['city'];
        $client->put("cities/$cityId", ['city' => $cityName]);
        PageCities::table(self::getCounties());
    } elseif (isset($request['btn-cancel'])) {
        PageCounties::table(self::getCounties());
    }
}
 
 
    private static function getCounties() : array
    {
        $client = new Client();
        $response = $client->get('counties');
 
        return $response['data'];
    }
 
    private static function showCities($countyId)
    {
        $client = new Client();
        $response = $client->get("counties/$countyId/cities");
    
        // Debugging: Log the response
        error_log(print_r($response, true)); // Log the response to see what is returned
    
        // Check if response data exists
        if (isset($response['data'])) {
            $cities = $response['data'];
        } else {
            $cities = []; // Default to an empty array if 'data' is not set
            error_log("No cities data found for county ID: $countyId");
        }
    
        // Ensure $cities is an array
        if (!is_array($cities)) {
            $cities = []; // Set to an empty array if not an array
        }
    
        PageCities::table($cities); // Now this will always receive an array
    }
}