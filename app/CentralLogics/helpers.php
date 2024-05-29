<?php

namespace App\CentralLogics;


use App\Model\BusinessSetting;
use App\Model\Currency;
use App\Model\DMReview;
use App\Model\Review;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Model\CustomerAddress;
use GuzzleHttp\Client;
use App\Model\OrderRoute;
use App\Model\Order;
use Exception;

class Helpers
{
    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }

    public static function combinations($arrays)
    {
        $result = [[]];
        foreach ($arrays as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property => $property_value]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    public static function get_business_settings($name)
    {
        $config = null;
        foreach (BusinessSetting::all() as $setting) {
            if ($setting['key'] == $name) {
                $config = json_decode($setting['value'], true);
            }
        }
        return $config;
    }

    public static function currency_code()
    {
        $currency_code = BusinessSetting::where(['key' => 'currency'])->first()->value;
        return $currency_code;
    }

    public static function currency_symbol()
    {
        $currency_symbol = Currency::where(['currency_code' => Helpers::currency_code()])->first()->currency_symbol;
        return $currency_symbol;
    }

    public static function send_push_notif_to_device($fcm_token, $data)
    {
        /*https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send*/
        $key = BusinessSetting::where(['key' => 'push_notification_key'])->first()->value;
        /*$project_id = BusinessSetting::where(['key' => 'fcm_project_id'])->first()->value;*/

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array(
            "authorization: key=" . $key . "",
            "content-type: application/json"
        );

        $postdata = '{
            "to" : "' . $fcm_token . '",
            "data" : {
                "title":"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_id":"' . $data['order_id'] . '",
                "type": "0",
                "is_read": 0
              }
        }';


        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function send_notif_to_device_new($fcm_token, $data)
    {

        $notification = [
            'title' => $data['title'],
            'body' => $data['description'],
            'image' => "https://winji.org/",
            'icon' => "https://winji.org/",
            'sound' => 'mySound'
        ];

        $extraNotificationData = ["message" => $notification, "moredata" => 'dd'];


        $fcmNotification = [
            'to' => $fcm_token,
            // 'registration_ids' => ["cfZ_NIbbTluaNpZB-xE3YI:APA91bF1pOGXeTbHkwh_-fJ3cw9vwwauaiqXcUc-GrxtIqHy4aI51btZW3HN0b3gNVaryD0J9QbrKF9QxH9YVS-zN0OFfG6LiPFIaX2yKBexQwsurRbZhFyBgkz0SNjcSGiTmoDAqtwo"], //multiple token array
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $fcmUrl = "https://fcm.googleapis.com/fcm/send";

        $server_key = BusinessSetting::where(['key' => 'push_notification_key'])->first()->value;
        $apiKey = "key=$server_key";


        $http = Http::withHeaders([
            'Authorization' => $apiKey,
            'Content-Type' => 'application/json'
        ])->post($fcmUrl, $fcmNotification);
        return 'success';
    }

    public static function new_chat_message($data, $user_fcm)
    {
        /*https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send*/
        $key = BusinessSetting::where(['key' => 'push_notification_key'])->first()->value;
        /*$project_id = BusinessSetting::where(['key' => 'fcm_project_id'])->first()->value;*/

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array(
            "authorization: key=" . $key . "",
            "content-type: application/json"
        );

        $screenChat = "chat";
        $postdata = '{
            "to" : "' . $user_fcm . '",
            "data" : {
                "title":"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_id":"""' . $data['order_id'] . '""",
                  "screen": "' . $screenChat . '",
                "type": "0",
                "is_read": 0
              }
        }';


        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }




    public static function send_notif_to_filtered_topic($data, $topic, $search)
    {
        /*https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send*/
        $key = BusinessSetting::where(['key' => 'push_notification_key'])->first()->value;
        /*$topic = BusinessSetting::where(['key' => 'fcm_topic'])->first()->value;*/
        /*$project_id = BusinessSetting::where(['key' => 'fcm_project_id'])->first()->value;*/

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array(
            "authorization: key=" . $key . "",
            "content-type: application/json"
        );
        $screen = "screenPage";
        $postdata = '{
            "to" : "/topics/' . $topic . '",
            "data" : {
                "title":"' . $data->title . '",
                "body" : "' . $data->description . '",
                "image" : "' . $data->image . '",
                "screen": "' . $search . '",
                "order_id": "' . $search . '",
                "type": "1",
                "is_read": 0
              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);
        return $result;
        // dd($topic);
    }


    public static function send_push_notif_to_topic($data)
    {
        /*https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send*/
        $key = BusinessSetting::where(['key' => 'push_notification_key'])->first()->value;
        /*$topic = BusinessSetting::where(['key' => 'fcm_topic'])->first()->value;*/
        /*$project_id = BusinessSetting::where(['key' => 'fcm_project_id'])->first()->value;*/

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array(
            "authorization: key=" . $key . "",
            "content-type: application/json"
        );
        $postdata = '{
            "to" : "/topics/notify",
            "data" : {
                "title":"' . $data->title . '",
                "body" : "' . $data->description . '",
                "image" : "' . $data->image . '",
                "type": "0",
                "is_read": 0
              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function rating_count($product_id, $rating)
    {
        return Review::where(['product_id' => $product_id, 'rating' => $rating])->count();
    }

    public static function dm_rating_count($deliveryman_id, $rating)
    {
        return DMReview::where(['delivery_man_id' => $deliveryman_id, 'rating' => $rating])->count();
    }

    public static function tax_calculate($product, $price)
    {
        if ($product['tax_type'] == 'percent') {
            $price_tax = ($price / 100) * $product['tax'];
        } else {
            $price_tax = $product['tax'];
        }
        return $price_tax;
    }

    public static function discount_calculate($product, $price)
    {
        if ($product['discount_type'] == 'percent') {
            $price_discount = ($price / 100) * $product['discount'];
        } else {
            $price_discount = $product['discount'];
        }
        return $price_discount;
    }



    public static function env_update($key, $value)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                $key . '=' . env($key),
                $key . '=' . $value,
                file_get_contents($path)
            ));
        }
    }

    public static function env_key_replace($key_from, $key_to, $value)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                $key_from . '=' . env($key_from),
                $key_to . '=' . $value,
                file_get_contents($path)
            ));
        }
    }

    public static  function remove_dir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") Helpers::remove_dir($dir . "/" . $object);
                    else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public static  function update_routes_orders($order)
    {
        /// lets sort all routes according to distance

        $order_routes = OrderRoute::where('order_id', $order->id)->get();
        ///////////////////// 1- FIRST I WILL GET ALL ROUTES DISTANCE AND DURATION //////////////////////////
        $client = new Client();
        $apiKey = 'AIzaSyAOmCMG__6_UEnJgBvvkWy_z6WX-MxFxJw';

        // Origin and destination coordinates
        $origin = "$order->pickup_latitude,$order->pickup_longitude";
        $routesCollection = collect([]);

        foreach ($order_routes as $route) {
            // Make an API request to get directions
            $destination = "$route->latitude,$route->longitude";

            $response = $client->get("https://maps.googleapis.com/maps/api/directions/json", [
                'query' => [
                    'origin' => $origin,
                    'destination' => $destination,
                    'key' => $apiKey,
                ]
            ]);

            // Parse the response as JSON
            $data = json_decode($response->getBody(), true);
            // Extract the distance and duration from the response
            $distance = $data['routes'][0]['legs'][0]['distance']['text'];
            $duration = $data['routes'][0]['legs'][0]['duration']['text'];

            $parts = explode(" ", $distance);

            if (is_numeric($parts[0])) {
                // Check if it's an integer or a double
                if (strpos($parts[0], '.') !== false) {
                    // It's a double
                    if ($parts[1] == 'm') {
                        $distance = (float)$parts[0] * 1000;
                    } else {
                        $distance = (float)$parts[0];
                    }
                } else {
                    // It's an integer
                    if ($parts[1] == 'm') {
                        $distance = (int)$parts[0] * 1000;
                    } else {
                        $distance = (int)$parts[0];
                    }
                }
            } else {
                // Handle the case when the first part is not numeric
                $distance = null; // Or handle it as needed
            }

            $route['distance'] = $distance;
            $route['duration'] = $duration;

            $routesCollection->push($route);
        }
        ///////////////////// NOW I ASSIGNED DISTANCE AND DURATION TO EACH ROUTE -- OSAMA  //////////////////////////

        //////////////////////// 2- SORT THE ROUTES BASED ON DSITANCE IN ASCENDING ORDER  //////////////////////////////
        $sortedRoutes = $routesCollection->sortBy('distance');
        $sorted_values = $sortedRoutes->values()->all();
        //////////////////////// SORTED //////////////////////////////

        // LETS RE-SAVE RECORDS IN THE DATABSE
        foreach ($sorted_values as $key => $sorted_value) {
            $route = OrderRoute::where('id', $sorted_value->id)->first();
            $route->distance = number_format($sorted_value['distance'], 1);;
            $route->duration = $sorted_value['duration'];
            $route->route_order = $key + 1;
            $route->save();
        }
    }

    public static function calculate_routes_duration($start_lat, $start_lng, $end_lat, $end_lng)
    {
        /// lets sort all routes according to distance

        $client = new Client();
        $apiKey = config('app.google_map_api_key');

        // Origin and destination coordinates
        $origin = "$start_lat,$start_lng";
        $destination = "$end_lat,$end_lng";

        $response = $client->get("https://maps.googleapis.com/maps/api/directions/json", [
            'query' => [
                'origin' => $origin,
                'destination' => $destination,
                'key' => $apiKey,
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        // Extract the distance and duration from the response
        $distance = $data['routes'][0]['legs'][0]['distance']['text'];
        $duration = $data['routes'][0]['legs'][0]['duration']['text'];

        $parts = explode(" ", $distance);

        if (is_numeric($parts[0])) {
            // Check if it's an integer or a double
            if (strpos($parts[1], 'km') !== false) {
                // It's in kilometers, convert to miles
                $distance = round((float)$parts[0] / 1.60934, 2); // Round result here
            } else {
                // It's already in miles or another unit, leave it as is
                $distance = (float)$parts[0];
            }
        } else {
            // Handle the case when the first part is not numeric
            $distance = null; // Or handle it as needed
        }

        $final_duration_mins = 0;
        // Extract numerical value and unit from the duration string
        // Define a mapping of units to minutes (you can expand this if needed)
        $unit_to_minutes = [
            'min' => 1,
            'mins' => 1,
            'minute' => 1,
            'minutes' => 1,
            'hour' => 60,
            'hours' => 60,
            'day' => 1440,
            'days' => 1440,
        ];

        // Initialize the total duration to zero
        $final_duration_mins = 0;

        // Use a regular expression to find all matches of value-unit pairs
        if (preg_match_all('/(\d+)\s+(\w+)/', $duration, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $duration_value = intval($match[1]);
                $duration_unit = strtolower($match[2]);

                if (isset($unit_to_minutes[$duration_unit])) {
                    // Calculate the time in minutes and add it to the total
                    $time_in_minutes = $duration_value * $unit_to_minutes[$duration_unit];
                    $final_duration_mins += $time_in_minutes;
                } else {
                    echo "Invalid duration unit: " . $duration_unit;
                }
            }
        } else {
            echo "Invalid duration format";
        }

        $route['distance'] = $distance;
        $route['duration'] = $final_duration_mins;
        return $route;
    }


    public static function formatDuration($minutes)
    {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        $formattedDuration = '';

        if ($hours > 0) {
            $formattedDuration .= $hours . ' hour';
            if ($hours > 1) {
                $formattedDuration .= 's';
            }
            $formattedDuration .= ' ';
        }

        if ($remainingMinutes > 0) {
            $formattedDuration .= $remainingMinutes . ' minute';
            if ($remainingMinutes > 1) {
                $formattedDuration .= 's';
            }
        }

        return $formattedDuration;
    }


    public static function calculate_routes_distance_info($order_routes, $order)
    {
        $saved_total_duration = 0;
        $saved_total_distance = 0;
         for ($i = 0; $i < count($order_routes); $i++){
             if($i == 0){
                $start_point_lat = $order->pickup_latitude;
                $start_point_lng = $order->pickup_longitude;
                                     
                $end_point_lat = $order_routes[$i]['latitude'];
                $end_point_lng = $order_routes[$i]['longitude'];
                }else{
                $start_point_lat = $order_routes[$i - 1]['latitude'];
                $start_point_lng = $order_routes[$i - 1]['longitude'];
                                     
                $end_point_lat = $order_routes[$i]['latitude'];
                $end_point_lng = $order_routes[$i]['longitude'];
                }  
                  
                try
                {   
                    $info = Helpers::calculate_routes_duration($start_point_lat, $start_point_lng, $end_point_lat, $end_point_lng);
                    $saved_total_duration = $saved_total_duration + $info['duration'];
                    $saved_total_distance = $saved_total_distance + $info['distance'];   
                }
                catch (Exception $e ){
                    $saved_total_duration = 0;
                    $saved_total_distance = 0; 
                }
         }
         $final_info['total_distance'] = $saved_total_distance;
         $final_info['total_duration'] = $saved_total_duration;
         return $final_info;
    }

}
