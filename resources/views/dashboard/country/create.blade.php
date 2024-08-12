@extends('layouts.master')
@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">

                        <div class="iq-card-body">
                            <form method="POST" action="{{ route('dashboard.countries.store') }}"
                                enctype="multipart/form-data">
                                @csrf


                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title"> country Information</h4>
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
                                <label for="fname">title EN:</label>
                                <input type="text" class="form-control" name="title_en" id="fname"
                                    placeholder="country in english ">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fname">Title Ar:</label>
                                <input type="text" class="form-control" name="title_ar" id="fname">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fname">Shortcut:</label>
                                <input type="text" class="form-control" name="shortcut" id="fname">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fname">Image:</label>
                                <input type="file" class="form-control" name="primary_image" id="fname" placeholder="--%">
                            </div>
                            <button type="submit" class="btn btn-primary">Add New country</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
