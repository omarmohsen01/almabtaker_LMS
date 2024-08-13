@extends('layouts.master')

@section('content')
<div id="content-page" class="content-page">
    <div class="container-fluid">
       <div class="row">
          <div class="col-sm-12 col-lg-12">
             <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                   <div class="iq-header-title">
                      <h4 class="card-title">Edit Party</h4>
                   </div>
                </div>
                <div class="iq-card-body">
                   <form id="form-wizard1" class="text-center mt-4" method="POST" action="{{ route('dashboard.parties.update',$party->id) }}" enctype="multipart/form-data">
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
                                <i class="ri-file-list-line"></i><span>About Party</span>
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
                                  <h3 class="mb-4">party Information:</h3>
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
                                     <input type="input" class="form-control" value="{{ $party->title_en }}" name="title_en" placeholder="Your Title" />
                                  </div>
                               </div>
                               <div class="col-md-6">
                                    <div class="form-group">
                                    <label><b>Title AR: *</b></label>
                                    <input type="input" class="form-control" value="{{ $party->title_ar }}" name="title_ar" placeholder="Your Title In Ar" />
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
                                  <h3 class="mb-4">Party Details:</h3>
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
                                     <textarea class="form-control" name="description_en" >{{ $party->description_en }}</textarea>
                                  </div>
                               </div>
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>Description AR: *</b></label>
                                        <textarea class="form-control" name="description_ar" >{{ $party->description_ar }}</textarea>
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
                                 <h3 class="mb-4">Party Date:</h3>
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
                                      <label><b>Day: *</b></label>
                                      <input class="form-control" value="{{ $party->day }}" name="day" type="date" >
                                   </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                       <label><b>Time: *</b></label>
                                       <input class="form-control" value="{{ $party->time }}"  name="time" type="time" >
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label><b>Price: *</b></label>
                                       <input class="form-control" value="{{ $party->price }}" name="price" type="number" >
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                     <div class="form-group">
                                        <label><b>Quantity: *</b></label>
                                        <input class="form-control" value="{{ $party->quantity }}" name="quantity" type="number" >
                                     </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                       <label><b>Address: *</b></label>
                                       <input class="form-control" value="{{ $party->address }}" name="address" type="input" >
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
                                <label><b>Upload Your Seat Image:</b></label>
                                <input type="file" class="form-control"  value="{{ $party->seat_image }}" name="seat_image"/>
                            </div>
                            <div class="form-group">
                                <label><b>Upload Your Ticket Image:</b></label>
                                <input type="file" class="form-control"  value="{{ $party->ticket_image }}" name="ticket_image"/>
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
