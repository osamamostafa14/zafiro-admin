<?php

namespace App\Http\Controllers\Api\V1;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Model\Order;
use App\Model\OrderRoute;
use GuzzleHttp\Client;

class OrderController extends Controller
{
    public function get_pending_list(Request $request)
    {
        $orders = Order::with('order_routes')
        ->where(['driver_id' => $request->user()->id, 'driver_status' => $request->status])
        ->has('order_routes', '>', 0) // Filter orders with more than or equal one order route
        ->get();

        return $orders;
        
        foreach($orders as $order){
            $saved_total_duration = 0;
            $saved_total_distance = 0;
            $total_price = 0;
            $total_price = OrderRoute::where('order_id', $order->id)->sum('price');
            for ($i = 0; $i < count($order->order_routes); $i++){
                
             if($i == 0){
                $start_point_lat = $order->pickup_latitude;
                $start_point_lng = $order->pickup_longitude;
                                     
                $end_point_lat = $order->order_routes[$i]['latitude'];
                $end_point_lng = $order->order_routes[$i]['longitude'];
                }else{
                $start_point_lat = $order->order_routes[$i - 1]['latitude'];
                $start_point_lng = $order->order_routes[$i - 1]['longitude'];
                                     
                $end_point_lat = $order->order_routes[$i]['latitude'];
                $end_point_lng = $order->order_routes[$i]['longitude'];
                }
                                     
                $info = Helpers::calculate_routes_duration($start_point_lat, $start_point_lng, $end_point_lat, $end_point_lng);
                $saved_total_duration = $saved_total_duration + $info['duration'];
                $saved_total_distance = $saved_total_distance + $info['distance'];
         }
         $order['total_duration'] = $saved_total_duration;
         $order['total_distance'] = $saved_total_distance;
         $order['total_price'] = $total_price;
        }
        
        return response()->json($orders, 200);
    }
        
    public function update_driver_status(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->driver_status = $request->status;
        $order->save();
        return response()->json(['message' => 'success'], 200);
    }
    
    public function route_status(Request $request)
    {
        $route = OrderRoute::find($request->route_id);
        $next_route_order = $route->route_order + 1;
        $next_route = OrderRoute::where(['route_order'=> $next_route_order, 'order_id'=>$request->order_id])->first();
        
        if($request->status == 'started'){
          $route->driver_status = 'started';
          $route->start_date = Carbon::now();
          $route->save();  
        }else if($request->status == 'arrived'){
          $route->driver_status = 'arrived';
          $route->arrival_date = Carbon::now();
          $route->save();  
        }else if($request->status == 'delivered'){
          $route->driver_status = 'delivered';
          $route->delivery_date = Carbon::now();
          $route->is_current_route = 0;
          $route->save();  
          
          if($next_route){
           $next_route->is_current_route = 1;
           $next_route->save();
          }
        }else if($request->status == 'canceled'){
          $route->driver_status = 'canceled';
          $route->is_current_route = 0;
          $route->cancellation_reason = $request->cancellation_reason;
          $route->save();  
          
          if($next_route){
           $next_route->is_current_route = 1;
           $next_route->save();
          }   
        }
        
        $status_list = ['delivered', 'canceled'];
        $finished_routes_count = OrderRoute::where('order_id', $request->order_id)->whereIn('driver_status', $status_list)->count();
        $routes_count = OrderRoute::where('order_id', $request->order_id)->count();
        
        if($finished_routes_count == $routes_count){
            $order = Order::find($request->order_id);
            $order->driver_status ='delivered'; 
            $order->save();
        }
        
        
        return response()->json(['message' => 'success'], 200);
    }

    public function update_route_delivery_method(Request $request)
    {
        
        $route = OrderRoute::find($request->route_id);
        $route->delivery_method = $request->delivery_method;
        $route->delivery_method_details = $request->details;
        $route->driver_status = 'delivered';
        $route->delivery_date = Carbon::now();
        $route->is_current_route = 0;

        $next_route_order = $route->route_order + 1;
        $next_route = OrderRoute::where(['route_order'=> $next_route_order, 'order_id'=>$request->order_id])->first();

        if($next_route){
            $next_route->is_current_route = 1;
            $next_route->save();
           }  

        $route->save();  

        
        $status_list = ['delivered', 'canceled'];
        $finished_routes_count = OrderRoute::where('order_id', $request->order_id)->whereIn('driver_status', $status_list)->count();
        $routes_count = OrderRoute::where('order_id', $request->order_id)->count();
        
        if($finished_routes_count == $routes_count){
            $order = Order::find($request->order_id);
            $order->driver_status ='delivered'; 
            $order->save();
        }
        
        
        return response()->json(['message' => 'success'], 200);
    }
    
    public function get_order_routes(Request $request)
    {
        $order = Order::find($request->order_id);
        $routes = OrderRoute::where('order_id', $request->order_id)->orderBy('route_order')->get();
        
        $routes_count = OrderRoute::where('order_id', $request->order_id)->count();
        $not_started_count = OrderRoute::where(['driver_status'=> 'not_started', 'order_id'=> $request->order_id])->count();
   
        
        ///////////////////// 1- FIRST I WILL GET ALL ROUTES DISTANCE AND DURATION //////////////////////////
        $client = new Client();

        // Replace 'YOUR_API_KEY' with your actual Google Maps API key
       
        $apiKey = config('app.google_map_api_key');

        // Origin and destination coordinates
        $origin = "$request->latitude,$request->longitude";
        $routesCollection = collect([]);
        
       foreach($routes as $route){
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
                if($parts[1] == 'm'){
                  $distance = (double)$parts[0] * 1000;  
                }else {
                   $distance = (double)$parts[0];   
                }
                
            } else {
                 // It's an integer
                if($parts[1] == 'm'){
                  $distance = (int)$parts[0] * 1000;  
                }else {
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
        
        
         // CHECK IF IT DRIVER DIDNT START YET //
        $first_route_id = $routesCollection[0]['id'];
        if($routes_count == $not_started_count){
          // IF YES THEN ASSIGN IS_CURRENT_ORDER TO THE FIRST ROUTE //
          $route = OrderRoute::where('id', $first_route_id)->first();
          $route->is_current_route = 1;
          $route->save();
          
          OrderRoute::where('id', '!=', $first_route_id)
          ->update([
              'is_current_route' => 0
          ]);
           
              // ASSIGN  is_current_route TEMPORARY HERE BECAUSE IT WILL BE SAVED ABOVE IN DATABASE , I WILL NEED TO RELOAD AGAIN TO GET ITS VALUE -- OSAMA//
           foreach($routesCollection as $key=>$sorted_value){
               if($key == 0){
                   $sorted_value['is_current_route'] = 1;
               }
           }
           return $routesCollection;
        }
        return $routesCollection;
    }

    public function load_items(Request $request)
    {
        $item_ids = json_decode($request->item_ids, true);
         
        OrderRoute::whereIn('id', $item_ids)
        ->update([
           'loaded' => 1
        ]);

        $order_id = OrderRoute::whereIn('id', $item_ids)->first()->order_id;
        Order::where('id', $order_id)
        ->update([
           'items_loaded' => 1
        ]);
      
        return response()->json(['message' => 'success'], 200);
    }
}