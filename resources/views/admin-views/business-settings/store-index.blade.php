@extends('layouts.admin.app')

@section('title','Settings')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">App setup</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.business-settings.update-setup')}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    @php($name=\App\Model\BusinessSetting::where('key','app_name')->first()->value)
                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">Store name</label>
                        <input type="text" name="store_name" value="{{$name}}" class="form-control"
                               placeholder="New Store" required>
                    </div>

                    <div class="row">
                      
                        @php($currency_code=\App\Model\BusinessSetting::where('key','currency')->first()->value)
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{trans('messages.currency')}}</label>
                                <select name="currency" class="form-control js-select2-custom">
                                    @foreach(\App\Model\Currency::orderBy('currency_code')->get() as $currency)
                                        <option
                                            value="{{$currency['currency_code']}}" {{$currency_code==$currency['currency_code']?'selected':''}}>
                                            {{$currency['currency_code']}} ( {{$currency['currency_symbol']}} )
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @php($app_version=\App\Model\BusinessSetting::where('key','app_version')->first()->value)
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">App version (Version on store)</label>
                                <input type="number" min="1" value="{{$app_version}}"
                                       name="app_version" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @php($phone=\App\Model\BusinessSetting::where('key','phone')->first()->value)
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{trans('messages.phone')}}</label>
                                <input type="text" value="{{$phone}}"
                                       name="phone" class="form-control"
                                       placeholder="" required>
                            </div>
                        </div>
                        @php($email=\App\Model\BusinessSetting::where('key','email_address')->first()->value)
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{trans('messages.email')}}</label>
                                <input type="email" value="{{$email}}"
                                       name="email" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>
                        @php($address=\App\Model\BusinessSetting::where('key','address')->first()->value)
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{trans('messages.address')}}</label>
                                <input type="text" value="{{$address}}"
                                       name="address" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>
                        
                        

                        @php($price_per_mile=\App\Model\BusinessSetting::where('key','price_per_mile')->first()->value)
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">Price per mile</label>
                                <input type="number" min="1" value="{{$price_per_mile}}"
                                       name="price_per_mile" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>

                        @php($price_per_minute=\App\Model\BusinessSetting::where('key','price_per_minute')->first()->value)
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">Price per minute</label>
                                <input type="number" min="1" value="{{$price_per_minute}}"
                                       name="price_per_minute" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>

                        @php($earning_type=\App\Model\BusinessSetting::where('key','earning_type')->first()->value)
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label">Earning type</label>
                                <select name="earning_type" class="form-control">
                                    <option value="per_mile" {{$earning_type=='per_mile'?'selected':''}}>Per mile
                                    </option>
                                    <option value="per_minute" {{$earning_type=='per_minute'?'selected':''}}>
                                        Per minute
                                    </option>
                                    <option value="per_minute_estimated" {{$earning_type=='per_minute_estimated'?'selected':''}}>
                                        Per minute (Estimated)
                                    </option>
                                </select>
                            </div>
                        </div>

                        @php($minimum_earning_minutes=\App\Model\BusinessSetting::where('key','minimum_earning_minutes')->first()->value)
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label">Minimum earning minutes (In case earning per minute is selected)</label>
                                <input type="number" min="1" value="{{$minimum_earning_minutes}}"
                                       name="minimum_earning_minutes" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>

                        @php($footer_text=\App\Model\BusinessSetting::where('key','footer_text')->first()->value)
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{trans('messages.footer')}} {{trans('messages.text')}}</label>
                                <input type="text" value="{{$footer_text}}"
                                       name="footer_text" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>
                    </div>

                    <!--<div class="row">-->
                      
                    <!--    <div class="col-md-6 col-12">-->
                    <!--        @php($ev=\App\Model\BusinessSetting::where('key','email_verification')->first()->value)-->
                    <!--        <div class="form-group">-->
                    <!--            <label>{{trans('messages.email')}} {{trans('messages.verification')}}</label><small style="color: red">*</small>-->
                    <!--            <div class="input-group input-group-md-down-break">-->
                                    <!-- Custom Radio -->
                    <!--                <div class="form-control">-->
                    <!--                    <div class="custom-control custom-radio">-->
                    <!--                        <input type="radio" class="custom-control-input" value="1" name="email_verification"-->
                    <!--                               id="ev1" {{$ev==1?'checked':''}}>-->
                    <!--                        <label class="custom-control-label" for="ev1">{{trans('messages.on')}}</label>-->
                    <!--                    </div>-->
                    <!--                </div>-->
                                    <!-- End Custom Radio -->

                                    <!-- Custom Radio -->
                    <!--                <div class="form-control">-->
                    <!--                    <div class="custom-control custom-radio">-->
                    <!--                        <input type="radio" class="custom-control-input" value="0" name="email_verification"-->
                    <!--                               id="ev2" {{$ev==0?'checked':''}}>-->
                    <!--                        <label class="custom-control-label" for="ev2">{{trans('messages.off')}}</label>-->
                    <!--                    </div>-->
                    <!--                </div>-->
                                    <!-- End Custom Radio -->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->

                    @php($logo=\App\Model\BusinessSetting::where('key','logo')->first()->value)
                    <div class="form-group">
                        <label>{{trans('messages.logo')}}</label><small style="color: red">* ( {{trans('messages.ratio')}} 3:1 )</small>
                        <div class="custom-file">
                            <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                            <label class="custom-file-label" for="customFileEg1">{{trans('messages.choose')}} {{trans('messages.file')}}</label>
                        </div>
                        <hr>
                        <center>
                            <img style="height: 100px;border: 1px solid; border-radius: 10px;" id="viewer"
                                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                                 src="{{asset('storage/app/public/store/'.$logo)}}" alt="logo image"/>
                        </center>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">{{trans('messages.submit')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
@endpush
