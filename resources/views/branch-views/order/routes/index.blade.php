@extends('layouts.branch.app')

@section('title','Add new route')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i>Add new route</h1>
                </div>
            </div>
        </div>
        
         
          <div class="row">
                 <div class="col-6">
                      <div class="form-group">
                          <label class="input-label" for="exampleFormControlSelect1">Order Routes By<span
                             class="input-label-secondary">*</span></label>
                            <select name="order_by" class="form-control" onchange="showOrHideDiv(this.value)" >
                                <option value="distance" {{$order['order_by']=='distance'?'selected':''}}>Distance</option>
                                <option value="order" {{$order['order_by']=='order'?'selected':''}}>Order</option>
                        </select>
           </div>
          </div>
         </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('branch.order.routes.store')}}" method="post">
                    @csrf
                    <input name="order_id" type="hidden" value="{{$order->id}}">

                 @for($i = 0; $i < $order->stops_number; $i++)
                  <h2>Route {{$i + 1}}</h2>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Customer Name</label>
                                <input type="text" name="customer_name_{{$i}}" class="form-control" placeholder="New Customer" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Items Price</label>
                                <input type="number" name="price_{{$i}}" class="form-control" placeholder="Items price" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row"  id="routeOrderDiv">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Route Order</label>
                                <input type="number" name="route_order_{{$i}}" class="form-control" value="{{$i + 1}}" placeholder="Route Order" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Address</label>
                                <input type="text" name="address_{{$i}}" class="form-control" placeholder="Address" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Latitude</label>
                                <input type="text" name="latitude_{{$i}}" class="form-control" placeholder="Latitude" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Longitude</label>
                                <input type="text" name="longitude_{{$i}}" class="form-control" placeholder="Longitude" required>
                            </div>
                        </div>
                    </div>

                    <hr>
                    @endfor
                     
                    <button type="submit" class="btn btn-primary">{{trans('messages.submit')}}</button>
                </form>
            </div>

            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
<script>
    function showOrHideDiv(selectedValue) {
        var routeOrderDiv = document.getElementById('routeOrderDiv');

        if (selectedValue === 'distance') {
            routeOrderDiv.style.display = 'none';
        } else {
            routeOrderDiv.style.display = 'block';
        }
    }
</script>

@endpush
