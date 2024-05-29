@extends('layouts.admin.app')

@section('title','Driver details')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link"
                                   href="{{route('admin.order.routes.update')}}">
                                    Orders
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Driver details</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">Order ID #{{$order_id}}</h1>
                        <span class="ml-2 ml-sm-3">
                        <i class="tio-date-range">
                        </i> {{date('d M Y H:i:s',strtotime($order['created_at']))}}
                        </span>
                    </div>
                 
                    <div class="d-sm-flex align-items-sm-center">
                        <b> Driver: {{$driverroutes->first()->delivery_routes->full_name ?? '-'}} </b>
                      
                    </div>
                    
                    <div class="row pt-3 pb-3">
                        <div class="col-3">
                            <div class="card">
                                 <!--Body-->
                                 <div class="card-body">
                                     <div class="media align-items-center"
                                          style="cursor:pointer">
                                         <h6 class="card-header-title pr-3">Total distance:</h6>
                                     </div>
                                     
                                     <div class="media align-items-center"
                                          style="cursor:pointer">
                                         <h4 class="card-header-title">{{$saved_total_distance}} Miles</h4>
                                     </div>
                                 </div>
                                  <!--End Body-->
                            </div>
                        </div>
                        
                        <div class="col-3">
                            <div class="card">
                                 <!--Body-->
                                 <div class="card-body">
                                     <div class="media align-items-center"
                                          style="cursor:pointer">
                                         <h6 class="card-header-title pr-3">Duration:</h6>
                                     </div>
                                     
                                     <div class="media align-items-center"
                                          style="cursor:pointer">
                                         <h4 class="card-header-title">{{App\CentralLogics\Helpers::formatDuration($saved_total_duration)}}</h4>
                                     </div>
                                 </div>
                                  <!--End Body-->
                            </div>
                        </div>

                    </div>
                    
                    
                    <div class="row border-top pt-3">
                        <div class="col-12">
                            <a href="{{route('admin.order.routes.add-route', [$order['id']])}}" class="btn btn-primary">
                                <i class="tio-add-circle-outlined"></i> Add new route
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-auto pr-3">
                <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle mr-1" href="" 
                   data-toggle="tooltip" data-placement="top" title="Call Driver">
                  <i class="fas fa-phone card-icon fa-2x pl-3"></i>
                </a>
                <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle"
                 href="{{route('admin.message.view',['driver_id' => $driverroutes[0]['driver_id'], 'order_id' => $order->id])}}" data-toggle="tooltip" data-placement="top" title="Chat with driver">
                  <i class="fas fa-comments card-icon fa-2x pl-3"></i> 
                </a>
            </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row" id="printableArea">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <div id="map" style="width: 100%; height: 400px;"></div>
            </div>

            <div class="col-lg-4">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title">Routes</h4>                      
                        <h6 class="card-header-title">Start time: {{\Carbon\Carbon::parse($order->date)->format('g:i A')}}</h6>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    @if($order)
                     @php
                      $total_duration = 0;
                    @endphp
                    
                        <div class="card-body">
                           
                         @for ($i = 0; $i < count($driverroutes); $i++)
                            @php
                            $route = $driverroutes[$i];
                           @endphp
                           
                           
                                    <!-- Modal -->
    <div class="modal fade routes-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
         aria-hidden="true" id="routes-modal-{{$route->id}}">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="mySmallModalLabel">Replace with a route</h5>
                    <button type="button" class="btn btn-xs btn-icon btn-ghost-secondary" data-dismiss="modal"
                            aria-label="Close">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                </div>
              <div class="modal-body">
               
                @foreach($driverroutes as $order_route)
                        <div class="col">
                            @if($order_route['id'] == $route->id)
                             <div class="replace-route" data-main-route-id="{{$order_route->id}}" data-replaceable-route-id="{{$route->id}}">
                                  <b>( {{$order_route->route_order}} )</b>  {{$order_route->address}}   
                             </div>
                            @else
                             <div class="replace-route" data-main-route-id="{{$order_route->id}}" data-replaceable-route-id="{{$route->id}}" style="cursor:pointer; color:blue;">
                                  <b>( {{$order_route->route_order}} )</b>  {{$order_route->address}}  
                             </div>
                            @endif
                            <hr>
                        </div>
                  @endforeach
                   </div>
        </div>
    </div>
    </div>
    <!-- End Modal -->
                            <div class="media align-items-center">
                                <!--<div class="icon icon-soft-info icon-circle mr-3" >-->
                                <!--</div>-->
                                
                                @php
                                $next_route_order = $route->route_order + 1;
                                $next_route = \App\Model\OrderRoute::where(['id'=>$order->id, 'route_order' => $next_route_order])->first();
                                @endphp
                                
                                @if($selected_route_id == $route->id)
                                 <i class="fas fa-map-marker-alt  mr-1" style="color: blue;"></i>
                                 <b class="pr-3">Point {{$route->route_order}}</b>
                                 @else
                                 <i class="fas fa-map-marker-alt  mr-1"></i>
                                 <b class="pr-3">Point {{$route->route_order}}</b>
                                 @endif
                                 <hr class="pr-3" style="border: none; border-left: 1px solid #dfdfdf; height: 5rem; margin-top: 0; margin-bottom: 0;">
                                 
                                 
                                 @php
                                     
                                     if($i == 0){
                                     $start_point_lat = $order->pickup_latitude;
                                     $start_point_lng = $order->pickup_longitude;
                                     
                                     $end_point_lat = $route->latitude;
                                     $end_point_lng = $route->longitude;
                                     }else{
                                     $start_point_lat = $driverroutes[$i - 1]['latitude'];
                                     $start_point_lng = $driverroutes[$i - 1]['longitude'];
                                     
                                     $end_point_lat = $route->latitude;
                                     $end_point_lng = $route->longitude;
                                     }
                                     
                                    
                                     try
                                      {   
                                        $duration = App\CentralLogics\Helpers::calculate_routes_duration($start_point_lat, $start_point_lng, $end_point_lat, $end_point_lng);
                                        $total_duration = $total_duration + $duration['duration']; 
                                    }
                                    catch (Exception $e ){
                                        $total_duration = $total_duration;
                                    }
                                     
                                     $order_date = \Carbon\Carbon::parse($order->date);
                                     $final_date = $order_date->addMinutes($total_duration);
                                     // Parse the original date and add the calculated interval
                                     
                                 @endphp
                                 
                                 
                                <div class="media-body">
                                    @if($selected_route_id == $route->id)
                                    <div class="select-route" data-select-route-id="{{$route->id}}" style="cursor:pointer">
                                        <p><span style="color: blue;">{{$route->address}}</span>.</p>
                                        <p><span style="color: blue;"><b>ETA: </b>{{ $final_date->format('g:i a') }} </span></p>
                                    </div>
                                    
                                    @else
                                    <div class="select-route" data-select-route-id="{{$route->id}}" style="cursor:pointer">
                                        <span class="text-body text-hover-primary">{{$route->address}}</span>
                                        <p><span><b>ETA: </b>{{ $final_date->format('g:i a') }} </span></p>

                                    </div>
                                    @endif
                                    
                                    <a class="dropdown-item" target="_blank" style="text-align: end" href="{{route('admin.order.routes.live-tracking',[ $route->id ])}}"> <i class="tio-car"></i> Live Tracking</a>


                                   
                          <!--<a data-toggle="modal" data-target=".routes-modal-sm" data-trip-id="{{$route['id']}}" style="cursor:pointer; color:orange;">Change the route order</a>-->
                                    <a data-toggle="modal" data-target="#routes-modal-{{$route->id}}" style="cursor:pointer; color:orange;">Change the route order</a>
                                </div>
                            </div>
                            <hr>
                           @endfor

                        </div>
                @endif
                <!-- End Body -->
                </div>
        
            </div>
        </div>
        <!-- End Row -->
        <input type="hidden" id="driverId" value="{{ $route->driver_id }}">
    </div>
    
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select-route').click(function() {
            var routeId = $(this).data('select-route-id');

            // Generate the URL for the named route
            var url = '{{ route("admin.order.routes.select-route-id") }}';

            // Send an AJAX request to your controller to save the time interval
            $.ajax({
                type: 'POST',
                url: url, // Use the generated URL
                data: {
                    _token: '{{ csrf_token() }}',
                    route_id: routeId,
                },
                success: function(response) {
                    window.location.reload();
                },
                error: function(xhr) {

                    console.error(xhr.responseText);
                }
            });
        });  



        /*start function to change route driver*/
        $('.driverid').change(function() {
            var driverId =$(this).val();
            var routeId = $(this).data('value');
            var url = '{{ route("admin.order.routes.editdriver") }}';

            $.ajax({
                type: 'POST',
                url: url, // Use the generated URL
                data: {
                    _token: '{{ csrf_token() }}',
                    driver_id: driverId,
                    route_id: routeId,
                },
                success: function(response) {
                    window.location.reload();
                },
                error: function(xhr) {

                    console.error(xhr.responseText);
                }
            });
        });
        /*end function to change route driver*/
    });


    $(document).ready(function() {
        $('.replace-route').click(function() {
            var mainRouteId = $(this).data('main-route-id');
            var replaceableRouteId = $(this).data('replaceable-route-id');

            // Generate the URL for the named route
            var url = '{{ route("admin.order.routes.route-order") }}';

            // Send an AJAX request to your controller to save the time interval
            $.ajax({
                type: 'POST',
                url: url, // Use the generated URL
                data: {
                    _token: '{{ csrf_token() }}',
                     main_route_id: mainRouteId,
                     replaceable_route_id: replaceableRouteId,
                },
                success: function(response) {
                    
                    window.location.reload();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
    
</script>


<script  src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_map_api_key') }}&libraries=places"></script>
 
<script>

// Live Tracking
//let zoom = 15;

var driverId = {{ $driver_id }};
    var url = "{{ route('admin.order.routes.get-driver-live-tracking', ['id' => ':driverId']) }}";
    url = url.replace(':driverId', driverId);

    // var carIcon = {
    //     url: '{{  asset('public/assets/car.png') }}',
    //   //  scaledSize: new google.maps.Size(32, 32), // Adjust the size of the icon as needed
    // };
  var map;
  var marker;
  var markers = [];
  var lat = {{ $order->pickup_latitude }};
  var lng = {{ $order->pickup_longitude }};
   
  var directionsService = new google.maps.DirectionsService();
  var directionsRenderer = new google.maps.DirectionsRenderer({
      //  suppressMarkers: true // Prevent DirectionsRenderer from creating markers
    });
     
  // Define the waypoints (locations) for the directions
  var waypoints = @json($waypoints_arr);  

  function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
      center: { lat: lat, lng: lng },
      zoom: 13
    });

    carMarker = new google.maps.Marker({
      position: { lat: lat, lng: lng },
      map: map,
      draggable: false, // Allow the marker to be moved
      icon: 'https://img.icons8.com/color/48/car-top-view.png', 
    });

    var orderRoutes = @json($order->order_routes);
    console.log("orderRoutes",orderRoutes)
    
    var markers = [];

    // orderRoutes.forEach(function(route) {
    //     var newMarker = new google.maps.Marker({
    //         position: { lat: route.latitude, lng: route.longitude },
    //         map: map,
    //         draggable: false,
    //         title: route.title,
    //         label: {
    //             text: route.route_order.toString(),
    //             color: 'white',
    //             fontSize: '12px',
    //             fontWeight: 'bold',
    //             fontFamily: 'Arial, sans-serif',
    //             backgroundColor: 'blue',
    //         },
    //     });
    //   //  markers.push(newMarker);

    // });
     
                // Add origin  marker
                var originMarker = new google.maps.Marker({
                  position: map.getCenter(),
                  map: map,
                title: 'Origin'
             });
        markers.push(carMarker);

    function updateMarkerPosition(newLatLng) {
        carMarker.setPosition(newLatLng);
      }


function updatePosition(lat, lng) {
    const newLatLng = new google.maps.LatLng(lat, lng);
    updateMarkerPosition(newLatLng);
}


// Update marker position and directions every 5 seconds
setInterval(function () {
    $.ajax({
        url: url, // Replace with your server endpoint to fetch updated data
        dataType: 'json',
        success: function (response) {
            updatePosition(response.latitude, response.longitude);
            // Update directions with new coordinates
        },
        error: function (error) {
            console.error("Error fetching data:", error);
        },
    });
}, 5000);
    // @foreach($order->order_routes as $route)
    //   var marker = new google.maps.Marker({
    //     position: { lat: {{ $route['latitude'] }}, lng: {{ $route['longitude'] }} },
    //     map: map,
    //     draggable: false, // Allow the marker to be moved
    //     title: '{{ $route['title'] }}', 
    //     label: {
    //       text: '{{$route['route_order']}}', // Text to display as an indicator
    //       color: 'white', // Text color
    //       fontSize: '12px', // Font size
    //       fontWeight: 'bold', // Font weight
    //       fontFamily: 'Arial, sans-serif', // Font family
    //       backgroundColor: 'blue', // Background color
    //     },
         
    //   });

    //   markers.push(marker);
    // @endforeach

  

     
    // Request directions based on waypoints
    var request = {
      origin: waypoints[0],
      destination: waypoints[waypoints.length - 1],
      waypoints: waypoints.slice(1, -1), // Exclude start and end as waypointss
      travelMode: google.maps.TravelMode.DRIVING, // You can use other travel modes like 'WALKING', 'BICYCLING', etc.
    };

    directionsService.route(request, function (response, status) {
      if (status === google.maps.DirectionsStatus.OK) {
        directionsRenderer.setDirections(response);
        directionsRenderer.setMap(map); // Add the directionsRenderer to the map

        // Update the car marker position
        var route = response.routes[0];
        var leg = route.legs[0];
        var steps = leg.steps;
        var stepIndex = 0;

    }

    });
  }

  google.maps.event.addDomListener(window, 'load', initMap);
  
</script>

<style>
    .text-primary {
  color: blue;
}

    .canceled-status{
        color:red;
    } 

    .started-status {
        color:green;
    }  
    .not-started-status {
        color:yellow;
    } 
    .arrived-status {
        color:powderblue;
    } 
    .delivered-status {
        color:gray;
    } 

</style>





