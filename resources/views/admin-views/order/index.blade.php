@extends('layouts.admin.app')

@section('title','Add new order')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i>Add new order</h1>
                </div>
            </div>
        </div>
        
        
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.order.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Title" required>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Order Type</label>
                                 <select class="form-control" name="order_type">
                                    <option value="drop-off">Drop Off</option>
                                    <option value="pick-up">Pick Up</option>
                                 </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Date</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Time</label>
                                <input type="time" name="time" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Pickup Location</label>
                                <input id="search-input" type="text" name="pickup_location" placeholder="Search for a location"  class="form-control" required>
                               
                            </div>
                        </div>
                    </div>
               
                    <input id="address-input" value="" type="hidden">
                    <input id="location-input" name="coordinates" value=""  type="hidden">
                    
                    <div id="map" style="width: 100%; height: 400px;"></div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Save</button>
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
