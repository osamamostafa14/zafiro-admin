@extends('layouts.admin.app')

@section('title','Order List')

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

@php 
$OrderStatusArr = [
    'canceled',
    'pending',
    'delivered',
    'processing',
    'finished',
    'undelivered' 
];
@endphp
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center mb-3">
                <div class="col-sm">
                    <h1 class="page-header-title">Orders
                        <span class="badge badge-soft-dark ml-2">{{\App\Model\Order::count()}}</span>
                    </h1>
                </div>
            </div>
            <!-- End Row -->

            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <span class="hs-nav-scroller-arrow-prev" style="display: none;">
              <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                <i class="tio-chevron-left"></i>
              </a>
            </span>

                <span class="hs-nav-scroller-arrow-next" style="display: none;">
              <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                <i class="tio-chevron-right"></i>
              </a>
            </span>

                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Orders List</a>
                    </li>
                    {{--<li class="nav-item">
                        <a class="nav-link" href="#" tabindex="-1" aria-disabled="true">Open</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" tabindex="-1" aria-disabled="true">Unfulfilled</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" tabindex="-1" aria-disabled="true">Unpaid</a>
                    </li>--}}
                </ul>

               
                <!-- End Nav -->
            </div>
            
            <!-- End Nav Scroller -->
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card">
            
            <!-- Header -->
            <div class="card-header">
                <div class="row justify-content-between align-items-center flex-grow-1">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <form action="javascript:" id="search-form">
                            <!-- Search -->
                            <div class="input-group input-group-merge input-group-flush">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                       placeholder="Search" aria-label="Search" required>
                                <button type="submit" class="btn btn-primary">search</button>

                            </div>
                            
                            <!-- End Search -->
                        </form>
                    </div>
                    <div class="col-lg-3">
                        <select class="form-control filter-status" name="filter-status">
                            <option value="{{ route('admin.order.list-search',['all']) }}" >Show All</option>
                            @foreach($OrderStatusArr as $status)
                            <option 
                            @if($status == request()->route('order_status'))
                            selected
                            @endif
                            value="{{ route('admin.order.list-search',[$status]) }}">{{ ucwords($status) }}</option>
                            @endforeach 
                        </select>
                    </div>
                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
           
            <div class="table-responsive datatable-custom">
                <table id="datatable"
                       class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                       style="width: 100%"
                       data-hs-datatables-options='{
                     "columnDefs": [{
                        "targets": [0],
                        "orderable": false
                      }],
                     "order": [],
                     "info": {
                       "totalQty": "#datatableWithPaginationInfoTotalQty"
                     },
                     "search": "#datatableSearch",
                     "entries": "#datatableEntries",
                     "pageLength": 25,
                     "isResponsive": false,
                     "isShowPaging": false,
                     "pagination": "datatablePagination"
                   }'>
                    <thead class="thead-light">
                    <tr>
                        <th class="">
                            {{trans('messages.#')}}
                        </th>
                        <th>Title</th>
                        <th>Driver</th>
                        <th>Order Status</th>
                        <th>{{trans('messages.actions')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($orders as $key=>$order)
                    @php($driver = \App\User::where('id', $order->driver_id)->first())
                    @php($routes_count = count($order->order_routes))
                        <tr class="">
                            <td class="">
                                {{$key+1}}
                            </td>
                            
                            <td>
                                {{$order['title']}}
                            </td>
                            <td>
                                @if($driver)
                              {{$driver->full_name}}
                              @else
                              No driver assigned
                              @endif
                            </td>
                            <td> 

                                <form action="{{ route('admin.order.update.status', ['id' => $order->id]) }}" id="form{{ $order->id }}" method="post">
                                    @csrf
                                    @method('PUT') 
                                    <select class="form-control new_status" name="new_status"   data-id="#form{{ $order->id }}">
                                        
                                        @foreach($OrderStatusArr as $status)
                                        <option
                                            @if($order->order_status == $status)
                                            @selected(true)
                                            @endif
                                        value="{{ $status }}">{{ ucwords($status) }}</option>
                                        @endforeach
                                       
                                    </select>
                                
                                    {{-- <button type="submit">Update Status</button> --}}
                                </form>
                              
                                 {{-- <select class="form-control">
                                    @foreach($OrderStatusArr as $status)
                                    <option>{{ $status }}</option>
                                    @endforeach
                                    
                                 </select> --}}
                            </td>
                            
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                        <i class="tio-settings"></i>
                                    </button>
                                    
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{route('admin.order.edit',[$order['id']])}}">
                                            <i class="tio-edit"></i> Edit
                                        </a>
                                        
                                        <!-- <a class="dropdown-item" href="{{route('admin.order.routes.add-route',[$order['id']])}}">
                                            <i class="tio-add-circle-outlined"></i> Add new route
                                        </a>
                                         -->
                                        <a class="dropdown-item" href="{{route('admin.order.routes.edit',[$order['id']])}}">
                                        <i class="tio-visible"></i> View
                                        </a>
                                        
                                        <a class="dropdown-item" href="{{route('admin.order.driver.assign-driver',[$order['id']])}}">
                                            <i class="tio-car"></i> Driver
                                        </a>
                                       
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->

            <!-- Footer -->
            <div class="card-footer">
                <!-- Pagination -->
                {!! $orders->links() !!}
                <!-- End Pagination -->
            </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')

    <script>
        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.order.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.card-footer').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });

      
        // Listen for the change event on the dropdown
        $('.new_status').change(function () {
         
            var id = $(this).attr('data-id'); 
            console.log("asdasd")
            // Trigger the form submission
            $(id).submit();
        });

        $('.filter-status').change(function () {
         
         var url = $(this).val(); 
          location.href=url; 
     });

        
   
    </script>
@endpush
