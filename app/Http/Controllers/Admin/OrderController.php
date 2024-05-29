<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Order;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Model\OrderRoute;
use App\User;
use GuzzleHttp\Client;
use App\CentralLogics\Helpers;
use App\Model\BusinessSetting;
use App\Model\Earning;
use Exception;

class OrderController extends Controller
{
    public function add_new()
    {
        return view('admin-views.order.index');
    }

    public function liveTracking($id)
    {
        $order_routes = OrderRoute::where('id', $id)->first();
        // echo "<prE>"; print_r($order_routes); die; 

        return view('admin-views.order.routes.live-tracking',compact('order_routes','id'));
    }

    public function getDriverLiveTracking($driver_id)
    {   
      //  $driverList = OrderRoute::where(['id' => $id])->get(['live_tracking_lat','live_tracking_long'])->first();  
      $driverList = User::where('id', $driver_id)->get(['latitude','longitude'])->first();
        return response()->json($driverList);
    }

    
    public function add_new_route($order_id)
    {
        $order = Order::find($order_id);
        return view('admin-views.order.routes.index', compact('order'));
    }
    
    public function add_route($order_id)
    {
        $order = Order::find($order_id);
        return view('admin-views.order.routes.add-route', compact('order'));
    }
    
    public function edit($order_id)
    {
        $order = Order::find($order_id);
        
        $dateString = $order->date;
        $date = Carbon::parse($dateString)->format('Y-m-d');
        $time = Carbon::parse($dateString)->format('H:i:s');
        return view('admin-views.order.edit', compact('order',  'date', 'time'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'new_status' => 'required|in:canceled,pending,delivered,processing,undelivered,finished',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['order_status' => $request->input('new_status')]);
        if($order->order_status == 'finished'){
            $driver_ids = OrderRoute::where('order_id', $order->id)->pluck('driver_id');
            foreach($driver_ids as $driver_id){
                $order_routes = OrderRoute::where(['driver_id'=> $driver_id, 'order_id'=> $order->id])->get();
                $distance_info = Helpers::calculate_routes_distance_info($order_routes, $order);
               // $earning_type = BusinessSetting::where(['key' => 'earning_type'])->first()->value;
                // if($earning_type == 'per_mile'){
                   
                // }
                // else if($earning_type == 'per_minute'){
                //     $price_per_mile = BusinessSetting::where(['key' => 'price_per_mile'])->first()->value;
                //     $amount = (float)$price_per_mile * $distance_info['total_distance'];
                // }

                $price_per_mile = BusinessSetting::where(['key' => 'price_per_mile'])->first()->value;
                $amount = (float)$price_per_mile * $distance_info['total_distance']; 
                
                
                $earning = new Earning();
                $earning->driver_id = $driver_id;
                $earning->order_id = $order->id;
                $earning->total_distance = $distance_info['total_distance'];
                $earning->total_duration = $distance_info['total_duration'];
                $earning->amount = $amount;
                $earning->save();
            }
        }
        Toastr::success('Order status updated successfully.');
        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
    
    public function edit_routes($order_id)
    {
        $order = Order::with('order_routes')->find($order_id);
        // echo "<pre>"; print_r($order); die; 
        
        $order_route = OrderRoute::where('order_id', $order->id)->first();

        
        $order_routes = OrderRoute::orderBy('route_order')->where('order_id', $order->id)->get();
 

        $drivers = User::where(['user_type'=>'driver', 'status' => 1])->get();
        //return count($order_routes);
      //  $takendrivers = OrderRoute::with('delivery_routes')->where('order_id', $order->id)->distinct()->get('driver_id');
        $driver_ids = OrderRoute::where('order_id', $order->id)->pluck('driver_id');
        $takendrivers = User::whereIn('id', $driver_ids)->orWhere('id', $order->driver_id)->get();


        if(count($order_routes) > 0){
            $selected_route_id = '';
            if (session()->has('selected_route_id') == false) {
                session()->put('selected_route_id', $order_route->id);
                $selected_route_id = $order_route->id;
            }else {
                $selected_route_id = session()->get('selected_route_id');
            }
            
            $waypoints_arr = [];
            $previous_route_order = '';
            
            $current_route = OrderRoute::where('id', $selected_route_id)->first();
            
            if($current_route){
    
            }else{
                session()->put('selected_route_id', null);
            }
    
            if (session()->has('selected_route_id') == false ||  OrderRoute::where('order_id', $order->id)->count() == 1) {
              $next_route_order = $current_route->route_order + 1;  
              $next_route = OrderRoute::where(['order_id' => $order->id, 'route_order' => $next_route_order])->first();
              
              if($next_route){
                $waypoints_arr[] = [
                    'lat' => floatval($order->pickup_latitude),
                    'lng' => floatval($order->pickup_longitude),
                   ];

                   $waypoints_arr[] = [
                    'lat' => floatval($next_route->latitude),
                    'lng' => floatval($next_route->longitude),
                   ];
              }
             
            }else{
              //  return $current_route->route_order;
              if($current_route->route_order == 1){
              $waypoints_arr[] = [
              'lat' => floatval($order->pickup_latitude),
              'lng' => floatval($order->pickup_longitude),
             ];
             
             $waypoints_arr[] = [
              'lat' => floatval($current_route->latitude),
              'lng' => floatval($current_route->longitude),
             ];
            }else {
              $previous_route_order = $current_route->route_order - 1;  
              $previous_route = OrderRoute::where(['order_id' => $order->id, 'route_order' => $previous_route_order])->first();
              
              $waypoints_arr[] = [
                'lat' => floatval($previous_route->latitude),
                'lng' => floatval($previous_route->longitude),
               ];
               
               $waypoints_arr[] = [
                'lat' => floatval($current_route->latitude),
                'lng' => floatval($current_route->longitude),
               ];
              
             }  
            }
            
            // foreach ($order->order_routes as $route) {
            //   $waypoints_arr[] = [
            //   'lat' => floatval($route->latitude),
            //   'lng' => floatval($route->longitude),
            //  ];
            // }
            
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
        }else{
            $waypoints_arr = [];
            $selected_route_id = 10000;
            $saved_total_duration = 0;
            $saved_total_duration = 0;
            $saved_total_distance = 0;
        }
       
        // echo "<pre>"; print_r( $order_routes); die; 
        return view('admin-views.order.routes.edit', 
        compact('order', 'waypoints_arr', 'selected_route_id', 'order_routes', 'saved_total_duration', 
        'saved_total_distance','drivers','takendrivers'));
    }

    /*start function function to change driver in specific route*/
    public function edit_driver(Request $request){
        $order_route=OrderRoute::find($request->route_id);
        $order_route->driver_id = $request->driver_id;
        $order_route->save();
        Toastr::success('Driver Changed Successfully!');
    }
    /*end function function to change driver in specific route*/

    
    /*start function that get driver coordinates*/
    public function getDriverLatLongLiveTracking($id)
    {  
        $driverList = User::where(['id' => $id])->get(['latitude','longitude'])->first();  
        return response()->json($driverList);
    }
    /*end function that get driver coordinates*/


    public function select_route_id(Request $request)
    {        
        $allRoutes = OrderRoute::where('is_current_route', 1)->get();
    
        foreach ($allRoutes as $route) {
            $route->is_current_route = 0;
            $route->save();
        }
        // Set the selected route to is_current_route 1

            $route = OrderRoute::find($request->route_id);
            if ($route) {
            $route->is_current_route = 1;
            $route->save();
            session()->put('selected_route_id', $request->route_id);
        }
        //$order = OrderRoute::where('id', $route_id)->first();
       // return redirect()->route('admin.order.routes.edit', ['order_id' => $order->id]);
    }

    
    public function store(Request $request)
    {
        $dateTimeString = $request->date . ' ' . $request->time;
        $timestamp = strtotime($dateTimeString);
        
        // CALCULATE COORDINATES
        $coordinates = $request->coordinates;
        $coordinatesArray = explode(",", $coordinates);
        $latitude = $coordinatesArray[0];
        $longitude = $coordinatesArray[1];
        
        $order = new Order();
        $order->title = $request->title;
        $order->pickup_location = $request->pickup_location;
        $order->pickup_latitude = $latitude;
        $order->pickup_longitude = $longitude;
        $order->order_type = $request->order_type; 
        $order->date = date('Y-m-d H:i:s', $timestamp);
        $order->save();
        
        Toastr::success('Order created!');
        return redirect()->route('admin.order.list');
    }
    
    public function update(Request $request)
    {
        $dateTimeString = $request->date . ' ' . $request->time;
        $timestamp = strtotime($dateTimeString);
        
        $order = Order::find($request->order_id);
        $order->title = $request->title;
        $order->order_by = $request->order_by;
        $order->order_type = $request->order_type;  
        $order->date = date('Y-m-d H:i:s', $timestamp);
        $order->save();
        
        Toastr::success('Order updated!');
        return redirect()->route('admin.order.list');
    }
    
    public function list(Request $request , $order_status='all')
    {
       if($order_status == 'all'){
          $orders = Order::paginate(10);
       }else{
         $orders = Order::where(['order_status' =>  $order_status])->paginate(10);
       }
       
        return view('admin-views.order.list', compact('orders'));
    }
    
    public function store_routes(Request $request)
    {
        $order = Order::find($request->order_id);
        for($i = 0; $i < $order->stops_number; $i++){  
        $route = new OrderRoute();
        $route->order_id = $order->id;
        $route->customer_name = $request->input('customer_name_'.$i);
        $route->price = $request->input('price_'.$i);
        $route->address = $request->input('address_'.$i);
        $route->latitude = $request->input('latitude_'.$i);
        $route->longitude = $request->input('longitude_'.$i);
        $route->route_order = $request->input('route_order_'.$i);

        $order->phone = $request->phone;
        $order->rx_or_item = $request->rx_or_item;
        $order->type_of_location =$request->type_of_location;
        $order->order_type = $request->order_type;
        $order->order_priority = $request->order_priority;
        $order->route_name = $request->route_name;
        
        // if($i == 0){
        //  $route->current_route = 1;
        // }
        $route->save(); 
        }
        
        Toastr::success('Routes added successfully!');
        return redirect()->route('admin.order.list');
    }
    
    public function store_route(Request $request)
    {
        $coordinates = $request->coordinates;

        $coordinatesArray = explode(",", $coordinates);

        $latitude =  (!empty($coordinatesArray[0])) ? $coordinatesArray[0] : '';
        $longitude =  (isset($coordinatesArray[1])) ? $coordinatesArray[1] : '';
       

        $order = Order::find($request->order_id);

        $route = new OrderRoute();
        $route->order_id = $order->id;
        $route->driver_id = $order->driver_id;

        $route->customer_name = $request->customer_name;
        $route->price = $request->price;
        $route->address = $request->address;
        $route->latitude = $latitude;
        $route->longitude = $longitude;

        $route->phone = $request->input('phone');
        $route->rx_or_item = $request->input('rx_or_item');
        $route->type_of_location = $request->input('type_of_location');
        $route->order_type = $request->input('order_type'); 
        $route->order_priority =  $request->input('order_priority'); 
        $route->route_name =  $request->input('route_name');  

        $route->save(); 
        
        Helpers::update_routes_orders($order);
        
        Toastr::success('Route added successfully!');
        return redirect()->route('admin.order.list');
    }
    
    public function update_routes(Request $request)
    {
        $order = Order::find($request->order_id);
        
        foreach($order->order_routes as $key=> $route){
        
        $route->customer_name = $request->input('customer_name_'.$key);
        $route->price = $request->input('price_'.$key);
        $route->address = $request->input('address_'.$key);
        $route->latitude = $request->input('latitude_'.$key);
        $route->longitude = $request->input('longitude_'.$key);
        $route->route_order = $request->input('route_order_'.$key);
        $route->save(); 
        }
        
        Toastr::success('Routes updated successfully!');
        //return redirect()->route('admin.order.list');
    }

    /*start function that insert notes into database*/
    public function store_notes(Request $request,$id){
        $route=OrderRoute::find($id);
        $route->notes=$request->notes;
        $route->save();
        Toastr::success('Notes Added Successfully');
        return back(); 
        //return response()->json(['sucess'=>$request->notes]);
    }
    /*end function that insert notes into database*/

    public function assign_driver($order_id)
    {
        $drivers = User::where(['user_type'=>'driver', 'status' => 1])->paginate(10);
        $order = Order::find($order_id);
      
        return view('admin-views.order.driver.assign-driver', compact('drivers','order')); 
    }

    /*start function that get driver routes*/
    public function driver_details($driver_id,$order_id){

        $driverroutes=OrderRoute::orderBy('route_order')->with('delivery_routes')->where(['order_id'=>$order_id,'driver_id'=>$driver_id])->get();

        $order = Order::with('order_routes')->find($order_id);        
        $order_route = OrderRoute::where('order_id', $order_id)->first();
        $order_routes = OrderRoute::orderBy('route_order')->where(['order_id'=> $order_id, 'driver_id' => $driver_id])->get();

        if(count($order_routes) > 0){
            $selected_route_id;
            if (session()->has('selected_route_id') == false) {
                session()->put('selected_route_id', $order_route->id);
                $selected_route_id = $order_route->id;
            }else {
                $selected_route_id = session()->get('selected_route_id');
            }
            
            $waypoints_arr = [];
            $previous_route_order;
            
            $current_route = OrderRoute::where('id', $selected_route_id)->first();
            if($current_route){
    
            }else{
                session()->put('selected_route_id', null);
            }
    
            if (session()->has('selected_route_id') == false) {
              $next_route_order = $current_route->route_order + 1;  
              $next_route = OrderRoute::where(['order_id' => $order_id, 'route_order' => $next_route_order])->first();
               $waypoints_arr[] = [
              'lat' => floatval($order->pickup_latitude),
              'lng' => floatval($order->pickup_longitude),
             ];
             
             $waypoints_arr[] = [
              'lat' => floatval($next_route_order->latitude),
              'lng' => floatval($next_route_order->longitude),
             ];
             
            }else{
              if($current_route->route_order == 1){
              $waypoints_arr[] = [
              'lat' => floatval($order->pickup_latitude),
              'lng' => floatval($order->pickup_longitude),
             ];
             
             $waypoints_arr[] = [
              'lat' => floatval($current_route->latitude),
              'lng' => floatval($current_route->longitude),
             ];
            }else {
              $previous_route_order = $current_route->route_order - 1;  
              $previous_route = OrderRoute::where(['order_id' => $order_id, 'route_order' => $previous_route_order])->first();
              
              $waypoints_arr[] = [
              'lat' => floatval($previous_route->latitude),
              'lng' => floatval($previous_route->longitude),
             ];
             
             $waypoints_arr[] = [
              'lat' => floatval($current_route->latitude),
              'lng' => floatval($current_route->longitude),
             ];
             }  
            }

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
                    $info = Helpers::calculate_routes_duration($start_point_lat, $start_point_lng, $end_point_lat, $end_point_lng);
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
        }else{
            $waypoints_arr = [];
            $selected_route_id = 10000;
            $saved_total_duration = 0;
            $saved_total_duration = 0;
            $saved_total_distance = 0;
        }
        
        return view('admin-views.order.driver.driver-details', compact('driver_id','order', 'waypoints_arr', 'selected_route_id', 'order_routes', 'saved_total_duration', 'saved_total_distance','driverroutes','order_id'));

    }
    /*end function that get driver routes*/

    
    public function store_assign(Request $request)
    {
        $order = Order::find($request->order_id);
        //$orderroute=OrderRoute::where('order_id',$request->order_id)->get();
        if($request->status == 'assign'){
        $order->driver_id = $request->driver_id; 
        //$orderroute->update('driver_id',$request->driver_id);
        OrderRoute::where('order_id', $request->order_id)->update(['driver_id' => $request->driver_id]);
        $order->save();
        Toastr::success('Driver assigned successfully!');
        }else{
        $order->driver_id = null; 
        OrderRoute::where('order_id', $request->order_id)->update(['driver_id' => $null]);
        $order->save();
        Toastr::success('Canceled successfully!'); 
        }
    }
    
    public function route_ordering(Request $request)
    {
        $main_route = OrderRoute::find($request->main_route_id);
        $replaceable_route = OrderRoute::find($request->replaceable_route_id);
        
        $main_route_order = $main_route->route_order;
        $replaceable_route_order = $replaceable_route->route_order;
        
        $main_route->route_order = $replaceable_route_order;
        $replaceable_route->route_order = $main_route_order;
        
        $replaceable_route->save();
        $main_route->save();
        Toastr::success('Route orders updated!'); 
    }

   

}
