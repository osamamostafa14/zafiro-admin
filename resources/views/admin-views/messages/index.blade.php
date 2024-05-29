@extends('layouts.admin.app')

@section('title','Messages')

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .conv-active {
            background: #f3f3f3 !important;
        }

        div.scroll-down {
        max-height: 300px;
        overflow-y: scroll;
    }
    </style>
@endpush

@section('content')

    <div class="content container-fluid" >
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link" 
                            href="{{ route('admin.order.driver.driver_details', ['driver_id' => $driver->id, 'order_id' => $order_id]) }}">Order route</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Driver Messages</li>
                        </ol>
                    </nav>
                </div>

                <div class="col-sm-auto">

                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row">
           
            <div class="col-lg-8 col-8" id="view-conversation">
               
                <div class="card mb-3 mb-lg-5">
    <!-- Header -->
    <div class="card-header">
        <div class="avatar avatar-lg avatar-circle">
            <img class="avatar-img" style="width: 54px;height: 54px"
                src="{{asset('storage/app/public/profile/'.$driver['image'])}}"
                onerror="this.src='{{asset('public/assets/admin')}}/img/160x160/img1.jpg'" alt="Image Description">
        </div>
        <h5 class="mb-0 mr-3">{{$driver->full_name}}</h5>

    </div>

    <div class="card-body" id="messages-card">
        <div class="row scroll-down">

        <div class="col-12 pt1 pb-1">
                <div
                    style="background:#fdffddd1;padding: 10px;border: 1px solid #80808057;border-radius: 10px; width: 50%">
                    <h6>Hii</h6>
                    <br>
                </div>
             
                <div class="col-12 pt-1 pb-1" id="message-list">
                @foreach($messages as $item)
                <div class="float-right"
                    style="background:#89faff47;padding: 10px;border:1px solid #80808057;border-radius: 10px; width: 50%">
                    <h6>{{$item['message']}}</h6>
                </div>
                @endforeach
            </div>
           
            </div>
            <div id="scroll-here"></div>
        </div>
    </div>
    <!-- Body -->
</div>
<form action="javascript:" method="post" id="reply-form">
    @csrf
    <input type="hidden" value="{{$order_id}}"></textarea>
    <div class="card mb-3 mb-lg-5">
        <!-- Body -->
        <div class="card-body">
            <label class="input-label">{{trans('messages.reply')}}</label>
            <!-- Quill -->
            <div class="quill-custom_">
                <textarea class="form-control" name="message"></textarea>
            </div>
            <!-- End Quill -->
        </div>
        <!-- Body -->

        <!-- Footer -->
        <div class="card-footer">
            <div class="d-flex justify-content-end">
                <button type="submit" onclick="replyConvs('{{route('admin.message.store')}}')"
                    class="btn btn-primary">{{trans('messages.send')}}
                </button>
            </div>
        </div>


        <!-- End Footer -->
    </div>
</form>

            </div>
        </div>
        <!-- End Row -->
    </div>

@endsection


<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
    $(document).ready(function () {
        $('.scroll-down').animate({
            scrollTop: $('#scroll-here').offset().top
        }, 0);
    });


    function replyConvs(url) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: url,
                data: $('#reply-form').serialize(),
                success: function (data) {
                    toastr.success('Message sent', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $('#view-conversation').html(data.view);
                },
                error() {
                    toastr.error('Reply message is required!', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }
        
</script>


