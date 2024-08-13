@extends('layouts.master')

@section('content')
<div id="content-page" class="content-page">
    <div class="container-fluid">
       <div class="row">
          <div class="col-sm-12 col-lg-12">
             <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                   <div class="iq-header-title">
                      <h4 class="card-title">Edit Visa</h4>
                   </div>
                </div>
                <div class="iq-card-body">
                   <form id="form-wizard1" class="text-center mt-4" method="POST" action="{{ route('dashboard.visas.update',$visa->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                      <ul id="top-tab-list" class="p-0">
                         <li class="active" id="account">
                            <a href="javascript:void();">
                            <i class="ri-information-line"></i><span>Information</span>
                            </a>
                         </li>
                         <li id="personal">
                            <a href="javascript:void();">
                            <i class="ri-file-list-line"></i><span>Details</span>
                            </a>
                         </li>
                         <li id="personal">
                            <a href="javascript:void();">
                                <i class="ri-file-list-line"></i><span>About Visa</span>
                            </a>
                         </li>
                         <li id="payment">
                            <a href="javascript:void();">
                            <i class="ri-camera-fill"></i><span>Image</span>
                            </a>
                         </li>
                      </ul>
                      <!-- fieldsets -->
                      <fieldset>
                         <div class="form-card text-left">
                            <div class="row">
                               <div class="col-7">
                                  <h3 class="mb-4">Visa Information:</h3>
                               </div>
                               <div class="col-5">
                                  <h2 class="steps">Step 1 - 4</h2>
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
                            <div class="row">
                               <div class="col-md-6">
                                  <div class="form-group">
                                     <label><b>Title EN: *</b></label>
                                     <input type="input" class="form-control" value="{{ $visa->title_en }}" name="title_en" placeholder="Your Title In En" />
                                  </div>
                               </div>
                               <div class="col-md-6">
                                    <div class="form-group">
                                    <label><b>Title AR: *</b></label>
                                    <input type="input" class="form-control" value="{{ $visa->title_ar }}" name="title_ar" placeholder="Your Title In Ar" />
                                    </div>
                                </div>
                            </div>
                         </div>
                         <button type="button" name="next" class="btn btn-primary next action-button float-right" value="Next">Next</button>
                      </fieldset>
                      <fieldset>
                         <div class="form-card text-left">
                            <div class="row">
                               <div class="col-7">
                                  <h3 class="mb-4">visa Details:</h3>
                               </div>
                               <div class="col-5">
                                  <h2 class="steps">Step 2 - 4</h2>
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
                            <div class="row">
                               <div class="col-md-6">
                                  <div class="form-group">
                                     <label><b>Description EN: *</b></label>
                                     <textarea class="form-control" name="description_en" >{{ $visa->description_en }}</textarea>
                                  </div>
                               </div>
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>Description AR: *</b></label>
                                        <textarea class="form-control" name="description_ar" >{{ $visa->description_ar }}</textarea>
                                    </div>
                                </div>
                            </div>
                         </div>
                         <button type="button" name="next" class="btn btn-primary next action-button float-right" value="Next">Next</button>
                         <button type="button" name="previous" class="btn btn-dark previous action-button-previous float-right mr-3" value="Previous">Previous</button>
                      </fieldset>
                      <fieldset>
                        <div class="form-card text-left">
                           <div class="row">
                              <div class="col-7">
                                 <h3 class="mb-4">visa Date:</h3>
                              </div>
                              <div class="col-5">
                                 <h2 class="steps">Step 3 - 4</h2>
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
                            <div class="row">
                                <div class="col-md-6">
                                   <div class="form-group">
                                      <label><b>Duration: *</b></label>
                                      <input class="form-control" value="{{ $visa->duration }}" name="duration" type="date" >
                                   </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                       <label><b>Visa Type: *</b></label>
                                       <input class="form-control" value="{{ $visa->visa_type }}"  name="visa_type" type="input" >
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label><b>Price: *</b></label>
                                       <input class="form-control" value="{{ $visa->price }}" name="price" type="number" >
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                     <div class="form-group">
                                        <label><b>Quantity: *</b></label>
                                        <input class="form-control" value="{{ $visa->quantity }}" name="quantity" type="number" >
                                     </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                       <label><b>Country: *</b></label>
                                        <select name="country_id" class="js-example-basic-single js-states form-control" id="countryOption">
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}" {{ ($visa->country_id==$country->id)?'checked':'' }}>{{ $country->title_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                 </div>
                            </div>
                        </div>
                        <button type="button" name="next" class="btn btn-primary next action-button float-right" value="Next">Next</button>
                        <button type="button" name="previous" class="btn btn-dark previous action-button-previous float-right mr-3" value="Previous">Previous</button>
                     </fieldset>
                      <fieldset>
                         <div class="form-card text-left">
                            <div class="row">
                               <div class="col-7">
                                  <h3 class="mb-4">Image Upload:</h3>
                               </div>
                               <div class="col-5">
                                  <h2 class="steps">Step 4 - 4</h2>
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
                            <div class="form-group">
                                <label><b>Upload Your Image:</b></label>
                                <input type="file" class="form-control"  value="{{ $visa->image }}" name="image"/>
                            </div>
                         </div>
                         <button type="submit" class="btn btn-primary next action-button float-right" value="Submit">Submit</button>
                         <button type="button" name="previous" class="btn btn-dark previous action-button-previous float-right mr-3" value="Previous">Previous</button>
                      </fieldset>
                   </form>
                </div>
             </div>
          </div>
       </div>
    </div>
</div>
@endsection
