@extends('layouts.branch.app')

@section('title','Routes Info')

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
                            <a class="breadcrumb-link" href="{{route('admin.order.routes.update')}}">
                                Orders
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Order details</li>
                    </ol>
                </nav>

                <div class="d-sm-flex align-items-sm-center">
                    <h1 class="page-header-title">Order ID #{{$order['id']}}</h1>
                    <span class="ml-2 ml-sm-3">
                        <i class="tio-date-range">
                        </i> {{date('d M Y H:i:s',strtotime($order['created_at']))}}
                    </span>
                </div>

                <div class="row pt-3 pb-3">
                    <div class="col-3">
                        <div class="card">
                            <!--Body-->
                            <div class="card-body">
                                <div class="media align-items-center" style="cursor:pointer">
                                    <h6 class="card-header-title pr-3">Total distance:</h6>
                                </div>

                                <div class="media align-items-center" style="cursor:pointer">
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
                                <div class="media align-items-center" style="cursor:pointer">
                                    <h6 class="card-header-title pr-3">Duration:</h6>
                                </div>

                                <div class="media align-items-center" style="cursor:pointer">
                                    <h4 class="card-header-title">{{App\CentralLogics\Helpers::formatDuration($saved_total_duration)}}</h4>
                                </div>
                            </div>
                            <!--End Body-->
                        </div>
                    </div>
                </div>


                <div class="row pt-3">
                    <div class="col-12">
                        <a href="{{route('branch.order.routes.add-route', [$order['id']])}}" class="btn btn-primary">
                            <i class="tio-add-circle-outlined"></i> Add new route
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-auto">
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle mr-1"
                       href="{{route('branch.customer.view',[$order['id']-1])}}"
                       data-toggle="tooltip" data-placement="top" title="Previous customer">
                        <i class="tio-arrow-backward"></i>
                    </a>
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle"
                       href="" data-toggle="tooltip"
                       data-placement="top" title="Next Route">
                        <i class="tio-arrow-forward"></i>
                    </a>
                </div>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="row" id="printableArea">
        <div class="col-lg-8 mb-3 mb-lg-0">
            <div class="card">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Routes</h4>
                    <h6 class="card-header-title">Start time: {{\Carbon\Carbon::parse($order->date)->format('g:i A')}}</h6>
                </div>

                <!-- Body -->
                @if($order)
                @php
                $total_duration = 0;
                @endphp

                <div class="card-body">

                    @for ($i = 0; $i < count($order_routes); $i++) @php $route=$order_routes[$i]; @endphp <!-- Modal -->
                        <div class="modal fade routes-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="routes-modal-{{$route->id}}">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title h4" id="mySmallModalLabel">Replace with a route</h5>
                                        <button type="button" class="btn btn-xs btn-icon btn-ghost-secondary" data-dismiss="modal" aria-label="Close">
                                            <i class="tio-clear tio-lg"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        @foreach($order_routes as $order_route)

                                        <div class="col">
                                            @if($order_route['id'] == $route->id)
                                            <div class="replace-route" data-main-route-id="{{$order_route->id}}" data-replaceable-route-id="{{$route->id}}">
                                                <b>( {{$order_route->route_order}} )</b> {{$order_route->address}}
                                            </div>
                                            @else
                                            <div class="replace-route" data-main-route-id="{{$order_route->id}}" data-replaceable-route-id="{{$route->id}}" style="cursor:pointer; color:blue;">
                                                <b>( {{$order_route->route_order}} )</b> {{$order_route->address}}
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

                        <div class="modal fade routes-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="add-note-modal-{{$route->id}}">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title h4" id="mySmallModalLabel">Add Route Note</h5>
                                        <button type="button" class="btn btn-xs btn-icon btn-ghost-secondary" data-dismiss="modal" aria-label="Close">
                                            <i class="tio-clear tio-lg"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    <form action="{{route('branch.order.routes.store-notes', [$route->id])}}" method="post" >
                                            @csrf
                                            <textarea name="notes" placeholder="Enter Route Note" class="form-control" cols="10" rows="2">{{ $route->notes ?? ''  }}</textarea>
                                            <button type="submit" class="btn btn-success mt-2">Save note</button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->

                        <!-- Modal -->
                        <div class="modal fade routes-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="routes-delivered-modal-{{$route->id}}">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title h4" id="mySmallModalLabel">Driver Info</h5>
                                        <button type="button" class="btn btn-xs btn-icon btn-ghost-secondary" data-dismiss="modal" aria-label="Close">
                                            <i class="tio-clear tio-lg"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col">
                                            <div class="replace-route" style="cursor:pointer; color:blue;">
                                                <b>{{(!empty($route->delivery_method )) ? $route->delivery_method : 'Not Available '}}</b>
                                                <b> {{(!empty($route->delivery_method_details )) ? $route->delivery_method_details :  'Not Available '}} </b>
                                            </div>

                                            <hr>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Delivered End Modal -->


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
                            $start_point_lat = $order_routes[$i - 1]['latitude'];
                            $start_point_lng = $order_routes[$i - 1]['longitude'];

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

                                <div class="form-group">
                                    <label class="input-label">Change Driver<span class="input-label-secondary">*</span></label>
                                    <select name="driverid" class="form-control driverid" data-value="{{$route->id}}">
                                        @if ($route->driver_id == null)
                                        <option value="" selected>Select Driver</option>
                                        @endif
                                        @foreach($drivers as $driver)
                                        <option value="{{$driver->id}}" {{$route->driver_id == $driver->id ? 'selected' : ''}}>
                                            {{$driver->full_name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--<a data-toggle="modal" data-target=".routes-modal-sm" data-trip-id="{{$route['id']}}" style="cursor:pointer; color:orange;">Change the route order</a>-->


                                <div class="row">
                                    <div class="col-6">
                                        <a data-toggle="modal" data-target="#routes-modal-{{$route->id}}" style="cursor:pointer;">
                                            <b>Change the route order</b> <i class="fas fa-sync-alt pl-3"></i>
                                        </a>


                                    </div>
                                    <div class="col-6">
                                        <a data-toggle="modal" data-target="#add-note-modal-{{$route->id}}" style="cursor:pointer;">
                                            Add Note <i class="fas fa-sticky-note pl-3"></i>
                                        </a>
                                    </div>
                                </div>

                                @if($route->driver_status == 'Delivered')
                                <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#routes-delivered-modal-{{$route->id}}">
                                    {{ $route->driver_status }}
                                </button>
                                @endif
                            </div>
                        </div>
                        <hr>
                        @endfor

                </div>
                @endif
                <!-- End Body -->
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Card -->
            <div class="card">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Assigned Drivers</h4>
                </div>
                <!-- End Header -->

                <div class="card-body">
                    @foreach($takendrivers as $takendriver)

                    <li class="list-inline-item">
                        <div class="col">
                            <div class="row" style="display: flex; align-items: center;">
                                <img class="avatar-img mr-3 ml-3" style="width: 50px !important; height: 50px; border-radius: 50%;" 
                                onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'" 
                                src="{{asset('storage/app/public/profile/'.$takendriver->image)}}" alt="Image Description">
                    
                                <!-- Spacer with a fixed width -->
                                <div style="width: 20px;"></div>

                                <a href="{{ route('branch.order.driver.driver_details', ['driver_id' => $takendriver->id, 'order_id' => $order['id']]) }}"
                                 style=" color: black; width: 150px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <b>{{$takendriver->full_name ?? '-'}}</b>
                                </a>

                                <a href="{{ route('branch.order.driver.driver_details', ['driver_id' => $takendriver->id, 'order_id' => $order['id']]) }}">
                                <span class="pl-3">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                                </a>
                                
                            </div>

                            <hr>
                        </div>
                    </li>
                    @endforeach
                </div>


            </div>

        </div>
    </div>
    <!-- End Row -->
</div>

@endsection



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_map_api_key') }}&libraries=places&callback=initMap"></script>

<script>
    $(document).ready(function() {
        $('.select-route').click(function() {
            var routeId = $(this).data('select-route-id');

            // Generate the URL for the named route
            var url = '{{ route("branch.order.routes.select-route-id") }}';

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
            var url = '{{ route("branch.order.routes.editdriver") }}';

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
            var url = '{{ route("branch.order.routes.route-order") }}';

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

 

<script>

    var carIcon = {
        url: '{{  asset('public/assets/car.png') }}',
        scaledSize: new google.maps.Size(32, 32), // Adjust the size of the icon as needed
    };
  var map;
  var marker;
  var markers = [];
  var lat = {{ $order->pickup_latitude }};
  var lng = {{ $order->pickup_longitude }};
   
  var directionsService = new google.maps.DirectionsService();
  var directionsRenderer = new google.maps.DirectionsRenderer({
        suppressMarkers: true // Prevent DirectionsRenderer from creating markers
    });
     
  // Define the waypoints (locations) for the directions
  var waypoints = @json($waypoints_arr);  

  function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
      center: { lat: lat, lng: lng },
      zoom: 13
    });

    marker = new google.maps.Marker({
      position: { lat: lat, lng: lng },
      map: map,
      draggable: false, // Allow the marker to be moved
    });
     
    @foreach($order->order_routes as $route)
      var marker = new google.maps.Marker({
        position: { lat: {{ $route['latitude'] }}, lng: {{ $route['longitude'] }} },
        map: map,
        draggable: false, // Allow the marker to be moved
        title: '{{ $route['title'] }}', 
        label: {
          text: '{{$route['route_order']}}', // Text to display as an indicator
          color: 'white', // Text color
          fontSize: '12px', // Font size
          fontWeight: 'bold', // Font weight
          fontFamily: 'Arial, sans-serif', // Font family
          backgroundColor: 'blue', // Background color
        },
         
      });

      markers.push(marker);
    @endforeach



     
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



        function updateObjectCoordinates() {
        // Make an AJAX request to fetch the updated coordinates of the object
        // Replace the URL and other parameters with your actual implementation
        $.ajax({
            url: `/zafiro-admin/admin/order/driver/get-drivers-live-tracking/${$('.driverid').val()}`,
            method: 'GET',
            success: function(response) {
            // Update the car marker position with the new coordinates
            lat = response.latitude;
            lng = response.longitude;
            var newPosition = new google.maps.LatLng(lat, lng);
            carMarker.setPosition(newPosition);
            },
            error: function(error) {
            console.error('Error fetching object coordinates:', error);
            }
        });

        // Schedule the next update after a certain interval (e.g., every 5 seconds)
        setTimeout(updateObjectCoordinates, 5000);
        }

        // Call the function to start updating the object coordinates
        updateObjectCoordinates();

    }

    });
  }

  google.maps.event.addDomListener(window, 'load', initMap);
  
</script>

<style>
    .text-primary {
  color: blue;
}
</style>





