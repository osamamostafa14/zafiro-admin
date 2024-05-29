@extends('layouts.admin.app')

@section('title','Add extra route')

@push('css_or_js')
<style>
    .green{
        color: #0e09f0
    }
    .red{
        color: red
    }
    .grey{
        color: #ea9e2e
    }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i>Add extra route</h1>
                </div>
            </div>
        </div>
        @php 
                        $typeOfLoc = [
                            'home','facility','hospital','clinic', 'work_order'
                          ];
                        $type = [
                            'dropoff','pickup'
                          ];
                          $Priorities = ['ASAP' , 'STAT' , 'Same Day' ,'Next Day' , '3 day']; 
                          $RouteName = ['green' => 'South OC' , 'red' => 'West LA', 'grey' => 'Misc']; 
                    @endphp
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.order.routes.store-route')}}" method="post">
                    @csrf
                    <input name="order_id" type="hidden" value="{{$order->id}}">

                    @php($max_route = App\Model\OrderRoute::where('order_id', $order->id)->max('route_order'))
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Customer Name</label>
                                <input type="text" name="customer_name" class="form-control" placeholder="Customer Name" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Items Price</label>
                                <input type="number" name="price" class="form-control" placeholder="Items price" required>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Phone </label>
                                <input type="text" name="phone" class="form-control" placeholder="Customer Phone" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Rx or item</label>
                                <input type="text" name="rx_or_item" class="form-control" placeholder="Rx or item" required>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Type of Locations</label>

                                <select class="form-control" name="type_of_location">
                                    @foreach( $typeOfLoc as $loc)
                                        <option value="{{ $loc }}"> {{  ucwords($loc) }}</option>
                                    @endforeach
                                   
                                </select>
                                
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Type</label>

                                <select class="form-control" name="order_type">
                                    @foreach( $type as $typ)
                                        <option value="{{ $typ }}"> {{  ucwords($typ) }}</option>
                                    @endforeach
                                   
                                </select>
                                
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Route Name</label>

                                <select class="form-control" name="route_name">
                                    <option> Route Name</option>
                                    @foreach( $RouteName as $color => $routeName )
                                        <option class="{{ $color }}" value="{{ $routeName }}"> {{  ucwords($routeName) }}</option>
                                    @endforeach
                                   
                                </select>
                                
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Select Priority</label>

                                <select class="form-control" name="order_priority">
                                    @foreach( $Priorities as $priority )
                                        <option value="{{ $priority }}"> {{  ucwords($priority) }}</option>
                                    @endforeach
                                   
                                </select>
                                
                            </div>
                        </div>
                       
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Search Location</label>
                                <input id="search-input" type="text" name="address" placeholder="Search for a location"  class="form-control" required>
                               
                            </div>
                        </div>
                    </div>
                    
                    
                    <input id="address-input" value="" type="hidden">
                    <input id="location-input" name="coordinates" value=""  type="hidden">
                    

                    <div id="map" style="width: 100%; height: 400px;"></div>

                    <hr>
                    <button type="submit" class="btn btn-primary">{{trans('messages.submit')}}</button>
                </form>
            </div>

            <!-- End Table -->
        </div>
    </div>

@endsection

 
<script  src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_map_api_key') }}&libraries=places"></script>

<script>
    var map;
    var marker;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: 30.009622248011077, lng: 30.964271800177325 },
            zoom: 13
        });

        // Create a marker initially without a position.
        marker = new google.maps.Marker({
            map: map,
            draggable: true, // Allow the marker to be moved
        });

        var searchBox = new google.maps.places.SearchBox(document.getElementById('search-input'));

        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            var place = places[0];

            // Update the marker position and map center.
            marker.setPosition(place.geometry.location);
            map.setCenter(place.geometry.location);
            map.setZoom(15);

            // Update the input field with coordinates.
            document.getElementById('location-input').value = place.geometry.location.lat() + ',' + place.geometry.location.lng();

            // Update the input field with the address.
            document.getElementById('address-input').value = place.formatted_address;
        });

        // Add a click event listener to the map.
        google.maps.event.addListener(map, 'click', function(event) {
            // Update the marker position.
            marker.setPosition(event.latLng);

            // Reverse geocode the clicked location to get an address.
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: event.latLng }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK && results[0]) {
                    // Update the input field with coordinates.
                    document.getElementById('location-input').value = event.latLng.lat() + ',' + event.latLng.lng();

                    // Update the input field with the address.
                    document.getElementById('address-input').value = results[0].formatted_address;
                    document.getElementById('search-input').value = results[0].formatted_address;
                }
            });
        });
    }

    google.maps.event.addDomListener(window, 'load', initMap);
</script>

