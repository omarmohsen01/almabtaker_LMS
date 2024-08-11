@extends('layouts.master')
@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">

                        <div class="iq-card-body">
                            <form method="POST" action="{{ route('dashboard.promo.store') }}"
                                enctype="multipart/form-data">
                                @csrf


                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title"> Promo Information</h4>
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
                                <label for="fname">code:</label>
                                <input type="text" class="form-control" name="code" id="fname"
                                    placeholder="Promo Code ">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fname">Expire Data:</label>
                                <input type="date" class="form-control" name="expire_data" id="fname">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fname">Discount:</label>
                                <input type="number" min="0" max="100" class="form-control" name="discount" id="fname" placeholder="--%">
                            </div>
                            <button type="submit" class="btn btn-primary">Add New Promo Code</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
