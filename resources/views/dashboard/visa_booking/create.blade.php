@extends('layouts.master')

@section('content')
<div id="content-page" class="content-page">
    <div class="container-fluid">
       <div class="row">
          <div class="col-sm-12 col-lg-12">
             <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                   <div class="iq-header-title">
                      <h4 class="card-title">Add New Matche</h4>
                   </div>
                </div>
                <div class="iq-card-body">
                   <form id="form-wizard1" class="text-center mt-4" method="POST" action="{{ route('dashboard.matches.store') }}" enctype="multipart/form-data">
                        @csrf
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
                            <i class="ri-roadster-line"></i><span>About Car</span>
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
                                  <h3 class="mb-4">Matche Information:</h3>
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
                                     <label><b>First Team EN: *</b></label>
                                     <input type="input" class="form-control" name="first_team_en" placeholder="Your First Team" />
                                  </div>
                               </div>
                               <div class="col-md-6">
                                    <div class="form-group">
                                    <label><b>First Team AR: *</b></label>
                                    <input type="input" class="form-control" name="first_team_ar" placeholder="Your First Team" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                       <label><b>Seconed Team EN: *</b></label>
                                       <input type="input" class="form-control" name="seconed_team_en" placeholder="Your seconed Team" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label><b>Seconed Team AR: *</b></label>
                                      <input type="input" class="form-control" name="seconed_team_ar" placeholder="Your Seconed Team" />
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
                                  <h3 class="mb-4">Matche Details:</h3>
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
                                     <textarea class="form-control" name="description_en" >Describe Your Ticket In EN</textarea>
                                  </div>
                               </div>
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>Description AR: *</b></label>
                                        <textarea class="form-control" name="description_ar" >Describe Your Ticket In Ar</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                       <label><b>Compitation EN: *</b></label>
                                       <input type="input" class="form-control" name="compitation_en" placeholder="Your Compitation In En" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label><b>Compitation AR: *</b></label>
                                      <input type="input" class="form-control" name="compitation_ar" placeholder="Your Compitation In Ar" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                       <label><b>Stadium EN: *</b></label>
                                       <input type="input" class="form-control" name="stadium_en" placeholder="Your Stadium In En" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label><b>Stadium AR: *</b></label>
                                      <input type="input" class="form-control" name="stadium_ar" placeholder="Your Stadium In Ar" />
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
                                 <h3 class="mb-4">Matche Date:</h3>
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
                                      <input class="form-control"  name="day" type="date" >
                                   </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                       <label><b>Time: *</b></label>
                                       <input class="form-control"  name="time" type="time" >
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label><b>Price: *</b></label>
                                       <input class="form-control"  name="price" type="number" >
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                     <div class="form-group">
                                        <label><b>Quantity: *</b></label>
                                        <input class="form-control"  name="quantity" type="number" >
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
                                <input type="file" class="form-control" name="seat_image"/>
                            </div>
                            <div class="form-group">
                                <label><b>Upload Your Ticket Image:</b></label>
                                <input type="file" class="form-control" name="ticket_image"/>
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
