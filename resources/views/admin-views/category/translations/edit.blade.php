@extends('layouts.admin.app')

@section('title','Category translation')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-translate"></i>{{$category->name}}: {{$language->name}} translation</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.category.translation.update')}}">
                    @csrf
                    <input name="category_id" type="hidden" value="{{$category->id}}">
                    <input name="language_code" type="hidden" value="{{$language->code}}">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label">Name</label>
                                <input type="text" name="name" value="{{$category_translation->name ?? ''}}" class="form-control" placeholder="Category name"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!--<div class="row">-->
                    <!--    <div class="col-md-6 col-12">-->
                    <!--        <div class="form-group">-->
                    <!--    <label class="input-label" >Short description</label>-->
                    <!--       <textarea type="text" name="description" value="{{$category_translation->description ?? ''}}" class="form-control">{{$trip_translation->description ?? ''}}</textarea>-->
                    <!--       </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                   
                    <hr>
                   
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>

@endsection

