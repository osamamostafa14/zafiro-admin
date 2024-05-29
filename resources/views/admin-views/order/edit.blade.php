@extends('layouts.admin.app')

@section('title','Edit order')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i>Edit order</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.order.update')}}" method="post">
                    @csrf
                    <input name="order_id" type="hidden" value="{{$order->id}}">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Title</label>
                                <input type="text" name="title" value="{{$order->title}}" class="form-control" placeholder="Title" required>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Stops number <span style="color:red;">(You can update it from the routes page)</span></label>
                                <input type="text" name="stops_number" value="{{$order->stops_number}}" class="form-control" placeholder="Stops number" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Date</label>
                                <input type="date" name="date" value="{{$date}}" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Time</label>
                                <input type="time" name="time" value="{{$time}}" class="form-control" required>
                            </div>
                        </div>
                    </div>
               
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1">Order Routes By<span
                                        class="input-label-secondary">*</span></label>
                                <select name="order_by" class="form-control" onchange="show_item(this.value)">
                                    <option value="distance" {{$order['order_by']=='distance'?'selected':''}}>Distance</option>
                                    <option value="order" {{$order['order_by']=='order'?'selected':''}}>Order</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label">Order Type</label>
                                 <select class="form-control" name="order_type">
                                    <option {{$order['order_type']=='drop-off'?'selected':''}} value="drop-off">Drop Off</option>
                                    <option  {{$order['order_type']=='pick-up'?'selected':''}} value="pick-up">Pick Up</option>
                                 </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>

            <!-- End Table -->
        </div>
    </div>

@endsection

