@extends('layouts.admin.app')

@section('title','Edit Driver')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-translate"></i>Live Tracking Page </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <form action="" method="post" enctype="multipart/form-data">
                @csrf
              
          
                <div class="row">
                    {{-- <div class="col-6">
                        <div class="form-group">
                            <label>Driver uploaded Invoice</label>
                             <div class="custom-file">
                                <input type="file" name="profile_image" id="customFileEg1" class="custom-file-input"
                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileEg1">{{trans('messages.choose')}} {{trans('messages.file')}}</label>
                            </div>  
                           
                        </div>
                    </div> --}}
                    <div class="col-6">
                        <div class="form-group">
                            <label class="input-label">Price</label>
                            <input type="text" name="price" value="{{$order_routes->price}}" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                
         
                <div class="row">

                    <div class="col-12">
                        <label>Live Tracking</label>
                    <div id="liveTrackingMap" style="height: 400px;"></div>
                    </div>
                </div>
                

                <hr>
                {{-- <button type="submit" class="btn btn-primary">{{ trans('messages.submit') }}</button> --}}
            </form>
          
            <input type="hidden" id="driverId" value="{{ request()->get('id') }}">

        </div>
        </div>
    </div>

@endsection

@push('script_2')
 

<script>
     let zoom = 15;
    let map;
    let marker;
    let directionsService;
    let directionsRenderer;
    var driverId = "{{ $id }}"; 
    var url = "{{ route('admin.order.routes.get-driver-live-tracking', ['id' => $id]) }}";

function initMap() {
    map = new google.maps.Map(document.getElementById('liveTrackingMap'), {
        center: { lat:  51.1657, lng: 10.4515 },
        zoom: zoom
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({ map: map });

    marker = new google.maps.Marker({
        position: map.getCenter(),
        map: map,
        icon: 'https://img.icons8.com/color/48/car-top-view.png', 
        title: 'Current Position',
        title: 'My Car'
    });
}

function updateDirections(lat, long) {
    var origin = marker.getPosition();
    var destination = new google.maps.LatLng(lat, long);

    var request = {
        origin: origin,
        destination: destination,
        travelMode: 'DRIVING'
    };

    directionsService.route(request, function (response, status) {
        if (status == 'OK') {
            directionsRenderer.setDirections(response);
        } else {
            console.error('Directions request failed due to ' + status);
        }
    });
}

function updateMarkerPosition(newLatLng) {
    marker.setPosition(newLatLng);
     // map.panTo(newLatLng);
    map.setCenter(newLatLng);
    map.setZoom(zoom);
    // map.setCenter(newLatLng);
}

function updatePosition(lat, lng) {
    const newLatLng = new google.maps.LatLng(lat, lng);
    updateMarkerPosition(newLatLng);
}

// Example: Call this function with your updated lat/lng values
// Replace these values with your own

// Update marker position and directions every 5 seconds
setInterval(function () {
    $.ajax({
        url: url, // Replace with your server endpoint to fetch updated data
        dataType: 'json',
        success: function (response) {
            updatePosition(response.live_tracking_lat, response.live_tracking_long);
            // Update directions with new coordinates
        },
        error: function (error) {
            console.error("Error fetching data:", error);
        },
    });
}, 5000);
       
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&libraries=places&callback=initMap"></script>
     
 @endpush