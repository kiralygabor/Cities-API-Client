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
        switch ($request){
            case isset($request['btn-home']):
                break;
            case isset($request['btn-counties']):
                PageCounties::table(self::getCounties());
                break;
            //counties
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
            case isset($request['btn-cancel']):
                PageCounties::table(self::getCounties());
                break;
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
                $client->post('cities', ['city' => $cityName]);
                PageCities::table(self::getCounties());
                break;
            case isset($request['btn-edit-city']):
                $cityId = $request['btn-edit-city'];
                $countyId = self::getCountyId($request);
                //$city = $client->get("counties/$countyId/cities/$cityId")['data'];
                $response = $client->get("counties/$countyId/cities/$cityId");
                var_dump($response);
                PageCities::editForm($city);
                break;
            case isset($request['btn-update-city']):
                $cityName = $request['city'];
                $countyId = self::getCountyId($request);
                $cityId = self::getCityId($request);
                $client->put("counties/$countyId/cities/$cityId", ['city' => $cityName]);
                PageCities::table(self::getCounties());
                break;
            case isset($request['btn-cancel']):
                PageCounties::table(self::getCounties());
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
        $cities = $response['data'] ?? [];

        PageCities::table($cities);
    }

    private static function getCountyId($request)
    {
        if(isset($request['id_county'])){
            $_SESSION['id_county'] = $request['id_county'];
            return $request['id_county'];
        }
        return $_SESSION['id_county'];
    }

    private static function getCityId($request)
    {
        if(isset($request['btn-edit-city'])){
            $_SESSION['btn-edit-city'] = $request['btn-edit-city'];
            return $request['btn-edit-city'];
        }
        return $_SESSION['btn-edit-city'];
    }
    
}