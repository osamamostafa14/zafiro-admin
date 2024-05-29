@extends('layouts.admin.app')

@section('title','Earning Details')

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
                                   href="{{route('admin.earnings.list')}}">
                                   Earnings
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Earning details</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">Earning ID #{{$earning['id']}}</h1>
                        <span class="ml-2 ml-sm-3">
                        <i class="tio-date-range">
                        </i> {{date('d M Y H:i:s',strtotime($earning['created_at']))}}
                        </span>
                    </div>
                   
                </div>

                <div class="col-sm-auto">
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle mr-1"
                       href="{{route('admin.earnings.view',[$earning['id']-1])}}"
                       data-toggle="tooltip" data-placement="top" title="Previous earning">
                        <i class="tio-arrow-backward"></i>
                    </a>
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle"
                       href="{{route('admin.earnings.view',[$earning['id']+1])}}" data-toggle="tooltip"
                       data-placement="top" title="Next earning">
                        <i class="tio-arrow-forward"></i>
                    </a>
                </div>
            </div>

            <div class="row" id="printableArea">
            <div class="col-lg-6">
                <!-- Card -->
                <div class="card">

                    <!-- Body -->
                    @if($earning->driver)
                        <div class="card-body">
                            <div class="media align-items-center" href="javascript:">
                                <div class="avatar avatar-circle mr-3">
                                    <img
                                        class="avatar-img"
                                        onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                        src="{{asset('storage/app/public/profile/'.$earning->driver->image)}}"
                                        alt="Image Description">
                                </div>
                                <div class="media-body">
                                <span
                                    class="text-body text-hover-primary">{{$earning->driver['full_name']}} </span>
                                </div>
                                <div class="media-body text-right">
                                    {{--<i class="tio-chevron-right text-body"></i>--}}
                                </div>
                            </div>
                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Order Info</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>
                                    <i class="tio-money mr-2"></i>
                                   Amount: ${{$earning->amount}}
                                </li>
                                <li>
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                    Distance: {{$earning->total_distance}} Miles
                                </li>

                                <i class="fas fa-clock mr-2"></i>
                                    Duration: {{$earning->total_duration}} Minutes
                                </li>
                            </ul>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>
                                <a data-toggle="modal" data-target="#release-modal-{{$earning->id}}" style="cursor:pointer; color:rgb(5, 5, 5);">Release payment</a>
                                
                                </li>
                            </ul>

                          
                          
                        </div>
                @endif
                <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
            </div>
        </div>

        <!-- End Page Header -->
        <div class="modal fade routes-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
         aria-hidden="true" id="release-modal-{{$earning->id}}">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="mySmallModalLabel">Are you sure? Money is released?</h5>
                    <button type="button" class="btn btn-xs btn-icon btn-ghost-secondary" data-dismiss="modal"
                            aria-label="Close">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                </div>
              <div class="modal-body">
                <form action="{{route('branch.order.routes.store-notes', [$earning->id])}}" method="post" >
                    @csrf
                    <div class="col">
                    <button  class="btn btn-success mt-2">No</button>
                    <button type="submit" class="btn btn-success mt-2">Yes</button>
                    </div>
                    
                </form>
                
                </div>
        </div>
    </div>
    </div>
        <!-- End Row -->
    </div>
@endsection

@push('script_2')

    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
@endpush
