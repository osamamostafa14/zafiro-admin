@extends('layouts.branch.app')

@section('title','Dashboard')

@push('css_or_js')

@endpush

@section('content')

  
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{trans('messages.welcome')}}, {{auth('branch')->user()->name}}.</h1>
                    <p class="page-header-text">Hello, here is what happening with Zafiro today..</p>
                </div>
           
            </div>
        </div>
        <!-- End Page Header -->

    </div>

@endsection

