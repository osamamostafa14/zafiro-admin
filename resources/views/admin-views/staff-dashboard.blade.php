@extends('layouts.admin.staff-app')

@section('title','Staff Dashboard')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{trans('messages.welcome')}}, {{auth('staff')->user()->name}}.</h1>
                    <p class="page-header-text">{{trans('messages.welcome_message')}}</p>
                </div>

                <div class="col-sm-auto">
                    <a class="btn btn-primary" href="{{route('admin.product.list')}}">
                        <i class="tio-premium-outlined mr-1"></i> {{trans('messages.products')}}
                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card card-body mb-3 mb-lg-5">
            <div class="row gx-lg-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="media" style="cursor: pointer" onclick="location.href='{{route('admin.orders.list',['pending'])}}'">
                        <div class="media-body">
                            <h6 class="card-subtitle">{{trans('messages.pending')}}</h6>
                            <span class="card-title h3">{{\App\Model\Order::where(['order_status'=>'pending'])->count()}}</span>
                        </div>
                        <span class="icon icon-sm icon-soft-secondary icon-circle ml-3">
                          <i class="tio-airdrop"></i>
                        </span>
                    </div>
                    <div class="d-lg-none">
                        <hr>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 column-divider-sm">
                    <div class="media" style="cursor: pointer" onclick="location.href='{{route('admin.orders.list',['confirmed'])}}'">
                        <div class="media-body">
                            <h6 class="card-subtitle">{{trans('messages.confirmed')}}</h6>
                            <span class="card-title h3">{{\App\Model\Order::where(['order_status'=>'confirmed'])->count()}}</span>
                        </div>
                        <span class="icon icon-sm icon-soft-secondary icon-circle ml-3">
                          <i class="tio-checkmark-circle"></i>
                        </span>
                    </div>
                    <div class="d-lg-none">
                        <hr>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 column-divider-lg">
                    <div class="media" style="cursor: pointer" onclick="location.href='{{route('admin.orders.list',['processing'])}}'">
                        <div class="media-body">
                            <h6 class="card-subtitle">{{trans('messages.processing')}}</h6>
                            <span class="card-title h3">{{\App\Model\Order::where(['order_status'=>'processing'])->count()}}</span>
                        </div>
                        <span class="icon icon-sm icon-soft-secondary icon-circle ml-3">
                          <i class="tio-running"></i>
                        </span>
                    </div>
                    <div class="d-lg-none">
                        <hr>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 column-divider-sm">
                    <div class="media" style="cursor: pointer" onclick="location.href='{{route('admin.orders.list',['out_for_delivery'])}}'">
                        <div class="media-body">
                            <h6 class="card-subtitle">{{trans('messages.out_for_delivery')}}</h6>
                            <span class="card-title h3">{{\App\Model\Order::where(['order_status'=>'out_for_delivery'])->count()}}</span>
                        </div>
                        <span class="icon icon-sm icon-soft-secondary icon-circle ml-3">
                          <i class="tio-bike"></i>
                        </span>
                    </div>
                    <div class="d-lg-none">
                        <hr>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->

 
    <!-- Stats -->
     
        <!-- End Stats -->

     
        <!-- End Row -->

      
    </div>
@endsection

@push('script')
    <script src="{{asset('public/assets/admin')}}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{asset('public/assets/admin')}}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script
        src="{{asset('public/assets/admin')}}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js"></script>
@endpush


@push('script_2')
    <script>
        // INITIALIZATION OF CHARTJS
        // =======================================================
        Chart.plugins.unregister(ChartDataLabels);

        $('.js-chart').each(function () {
            $.HSCore.components.HSChartJS.init($(this));
        });

        var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

        // CALL WHEN TAB IS CLICKED
        // =======================================================
        $('[data-toggle="chart-bar"]').click(function (e) {
            let keyDataset = $(e.currentTarget).attr('data-datasets')

            if (keyDataset === 'lastWeek') {
                updatingChart.data.labels = ["Apr 22", "Apr 23", "Apr 24", "Apr 25", "Apr 26", "Apr 27", "Apr 28", "Apr 29", "Apr 30", "Apr 31"];
                updatingChart.data.datasets = [
                    {
                        "data": [120, 250, 300, 200, 300, 290, 350, 100, 125, 320],
                        "backgroundColor": "#377dff",
                        "hoverBackgroundColor": "#377dff",
                        "borderColor": "#377dff"
                    },
                    {
                        "data": [250, 130, 322, 144, 129, 300, 260, 120, 260, 245, 110],
                        "backgroundColor": "#e7eaf3",
                        "borderColor": "#e7eaf3"
                    }
                ];
                updatingChart.update();
            } else {
                updatingChart.data.labels = ["May 1", "May 2", "May 3", "May 4", "May 5", "May 6", "May 7", "May 8", "May 9", "May 10"];
                updatingChart.data.datasets = [
                    {
                        "data": [200, 300, 290, 350, 150, 350, 300, 100, 125, 220],
                        "backgroundColor": "#377dff",
                        "hoverBackgroundColor": "#377dff",
                        "borderColor": "#377dff"
                    },
                    {
                        "data": [150, 230, 382, 204, 169, 290, 300, 100, 300, 225, 120],
                        "backgroundColor": "#e7eaf3",
                        "borderColor": "#e7eaf3"
                    }
                ]
                updatingChart.update();
            }
        })


        // INITIALIZATION OF BUBBLE CHARTJS WITH DATALABELS PLUGIN
        // =======================================================
        $('.js-chart-datalabels').each(function () {
            $.HSCore.components.HSChartJS.init($(this), {
                plugins: [ChartDataLabels],
                options: {
                    plugins: {
                        datalabels: {
                            anchor: function (context) {
                                var value = context.dataset.data[context.dataIndex];
                                return value.r < 20 ? 'end' : 'center';
                            },
                            align: function (context) {
                                var value = context.dataset.data[context.dataIndex];
                                return value.r < 20 ? 'end' : 'center';
                            },
                            color: function (context) {
                                var value = context.dataset.data[context.dataIndex];
                                return value.r < 20 ? context.dataset.backgroundColor : context.dataset.color;
                            },
                            font: function (context) {
                                var value = context.dataset.data[context.dataIndex],
                                    fontSize = 25;

                                if (value.r > 50) {
                                    fontSize = 35;
                                }

                                if (value.r > 70) {
                                    fontSize = 55;
                                }

                                return {
                                    weight: 'lighter',
                                    size: fontSize
                                };
                            },
                            offset: 2,
                            padding: 0
                        }
                    }
                },
            });
        });
    </script>
@endpush
