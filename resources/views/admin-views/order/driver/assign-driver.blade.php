@extends('layouts.admin.app')

@section('title','Order List')

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center mb-3">
                <div class="col-sm">
                    <h1 class="page-header-title">
                        <span class="badge badge-soft-dark ml-2">Assign Driver</span>
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
                        <a class="nav-link active" href="#">Drivers List</a>
                    </li>
                   
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
                        <th>Name</th>
                        <th>{{trans('messages.actions')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($drivers as $key=>$driver)
                        <tr class="">
                            <td class="">
                                {{$key+1}}
                            </td>
                            
                            <td>
                                {{$driver['full_name']}}
                                @if($order->driver_id == $driver['id'])
                                <b class="pl-2" style="color:green; font-style:italic;">Assigned</b>
                                @endif
                            </td>
                            
                            <td>
                                @if($order->driver_id == $driver['id'])
                                <a class="btn btn-outline-secondary assign-driver"
                                 data-driver-id="{{$driver['id']}}" data-order-id="{{$order->id}}" data-assign-status="cancel">
                                            </i> Cancel
                                 </a>
                                @else
                                <a class="btn btn-outline-primary assign-driver" 
                                 data-driver-id="{{$driver['id']}}" data-order-id="{{$order->id}}" data-assign-status="assign"> Assign
                                 </a>
                                @endif
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
    </script>
    
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.assign-driver').click(function() {
            var driverId = $(this).data('driver-id');
            var orderId = $(this).data('order-id');
            var assignStatus = $(this).data('assign-status');

            // Generate the URL for the named route
            var url = '{{ route("admin.order.driver.store-assign") }}';

            // Send an AJAX request to your controller to save the time interval
            $.ajax({
                type: 'POST',
                url: url, // Use the generated URL
                data: {
                    _token: '{{ csrf_token() }}',
                    driver_id: driverId,
                    order_id: orderId,
                    status: assignStatus,
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


@endpush