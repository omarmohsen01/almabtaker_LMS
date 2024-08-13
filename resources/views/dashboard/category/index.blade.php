@extends('layouts.master')
@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                   <x-dashboard.alert />

                        @foreach ($categories as $category)

                        <div class="iq-card-body">
                            <form method="POST" action="{{ route('dashboard.categories.update',$category->id) }}"
                                enctype="multipart/form-data">
                                @csrf

                                @method('PUT')
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title"> category Information</h4>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                            <div class="iq-card-body">
                                <div class="form-group col-md-6">
                                    <label for="fname">title EN:{{ $category->title_en }}</label>
                                    {{-- <input type="text" class="form-control" name="title_en" id="fname"
                                        placeholder="Category in english "> --}}
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="fname">Image:</label>
                                    <input type="file" class="form-control" name="primary_image" id="fname" placeholder="--%">
                                </div>
                                <button type="submit" class="btn btn-primary">Update {{ $category->title_en }}</button>
                                </form>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
