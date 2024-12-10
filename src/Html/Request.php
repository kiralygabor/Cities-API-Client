<?php
 /**
 * @author Nagy Gergely, Király Gábor 
 **/
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
        switch ($request){
            case isset($request['btn-home']):
                break;
                
            //counties
            case isset($request['btn-counties']):
            case isset($request['btn-cancel']):
                PageCounties::table(self::getCounties());
                break;
            case isset($request['btn-del-county']);
                $client->delete('counties', $request['btn-del-county']);
                PageCounties::table(self::getCounties());
                break;
            case isset($request['btn-save-county']);
                $countyName = $request['name'];
                $client->post('counties', ['name' => $countyName]);
                PageCounties::table(self::getCounties());
                break;
            case isset($request['btn-edit-county']):
                $countyId = $request['btn-edit-county'];
                $county = $client->get("counties/$countyId")['data'];
                PageCounties::editForm($county);
                break;
            case isset($request['btn-update-county']):
                $countyId = $request['id'];
                $countyName = $request['name'];
                $client->put("counties/$countyId", ['name' => $countyName]);
                PageCounties::table(self::getCounties());
                break;

            //cities
            case isset($request['btn-cities']):
                $countyId = self::getCountyId($request);
                PageCities::dropdown(self::getCounties(), $countyId);
                self::showCities($countyId); 
                break;
            case isset($request['btn-del-city']);
                $client->delete('cities', $request['btn-del-city']);
                $countyId = self::getCountyId($request);
                PageCities::dropdown(self::getCounties(), $countyId);
                self::showCities($countyId); 
                break;
            case isset($request['btn-save-city']);
                $cityName = $request['city'];
                $zipCode = $request['zip_code'];
                $countyId = self::getCountyId($request);
                $client->post('cities', ['city' => $cityName, 'id_county' => $countyId, 'zip_code' => $zipCode]);
                PageCities::dropdown(self::getCounties(), $countyId);
                self::showCities($countyId);
                break;
            case isset($request['btn-edit-city']):
                $cityId = $request['btn-edit-city'];
                $city = $client->get("cities/$cityId")['data'][0];
                PageCities::editForm($city);
                break;
            case isset($request['btn-update-city']):
                $cityName = $request['city'];
                var_dump($cityName);
                $countyId = self::getCountyId($request);
                var_dump($countyId);
                $cityId = self::getCityId($request);
                var_dump($cityId);
                $client->put("cities/$cityId", ['city' => $cityName]);
                PageCities::dropdown(self::getCounties(), $countyId);
                self::showCities($countyId); 
                break;
            case isset($request['btn-alphabet']):
                $letter = $request['btn-alphabet']; 
                $countyId = self::getCountyId($request); 
                self::showCitiesByLetter($countyId, $letter);
                break;
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
    error_log("API Response for cities: " . print_r($response, true));
    $cities = $response['data'] ?? [];

    PageCities::table($cities);
}

private static function getCountyId($request)
{
    if (isset($request['id_county'])) {
        $_SESSION['id_county'] = $request['id_county'];
        return $request['id_county'];
    }
    return $_SESSION['id_county'] ?? null; 
}

    private static function getCityId($request)
    {
        if(isset($request['id'])){
            $_SESSION['id'] = $request['id'];
            return $request['id'];
        }
        return $_SESSION['id'];
    }

    private static function showCitiesByLetter($countyId, $letter)
    {
        $client = new Client();
        $response = $client->get("counties/$countyId/cities");
        $cities = $response['data'] ?? [];
 
        $filteredCities = array_filter($cities, function($city) use ($letter) {
            return strtoupper(substr($city['city'], 0, 1)) === strtoupper($letter);
        });
 
        PageCities::table(array_values($filteredCities));
    }
    
}