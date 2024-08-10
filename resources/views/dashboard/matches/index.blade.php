@extends('layouts.master')
@section('content')
<div id="content-page" class="content-page">
    <div class="container-fluid">
       <div class="row">
          <div class="col-sm-12">
                <div class="iq-card">
                   <div class="iq-card-header d-flex justify-content-between">
                      <div class="iq-header-title">
                         <h4 class="card-title">matche List</h4>
                      </div>
                   </div>
                   <x-dashboard.alert />
                   <div class="iq-card-body">
                      <div class="table-responsive">
                        <a class="btn btn-primary" style="margin-bottom: 15px" href="{{ route('dashboard.matches.create') }}">Add matche</a>

                         <div class="row justify-content-between">
                            {{-- <div class="col-sm-12 col-md-6">
                               <div id="user_list_datatable_info" class="dataTables_filter">
                                <form class="mr-3 position-relative d-flex" action="{{ URL::current() }}" method="get">
                                    <div class="form-group mr-4 mb-0 flex-grow-1">
                                        <input type="input" name="name" class="form-control" id="exampleInputSearch" placeholder=" Name" aria-controls="user-list-table">
                                    </div>
                                    <div class="form-group mr-4 mb-0 flex-grow-1">
                                        <input type="email" name="email" class="form-control" id="exampleInputSearch" placeholder="Email" aria-controls="user-list-table">
                                    </div>
                                    <div class="form-group mr-4 mb-0 flex-grow-1">
                                        <input type="number" name="phone" class="form-control" id="exampleInputSearch" placeholder="Phone Number" aria-controls="user-list-table">
                                    </div>
                                    <div class="form-group mr-4 mb-0" >
                                        <select class="form-control" name="status" placeholder="Status" id="exampleFormControlSelect1" style="width:150px">
                                            <option selected="" value="">All</option>
                                            <option value="1" @selected(request('status')=='ACTIVE')>ACTIVE</option>
                                            <option value="0" @selected(request('status')=='INACTIVE')>INACTIVE</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-dark">Search</button>
                                </form>

                               </div>
                            </div> --}}
                            {{-- <div class="col-sm-12 col-md-6">
                               <div class="user-list-files d-flex float-right">
                                  <a class="iq-bg-primary" href="javascript:void();" >
                                     Print
                                   </a>
                                  <a class="iq-bg-primary" href="javascript:void();">
                                     Excel
                                   </a>
                                   <a class="iq-bg-primary" href="javascript:void();">
                                     Pdf
                                   </a>
                                 </div>
                            </div> --}}
                         </div>
                         <table id="user-list-table" class="table table-striped table-bordered mt-4" role="grid" aria-describedby="user-list-page-info">
                           <thead>
                               <tr>
                                  <th></th>
                                  <th>Team</th>
                                  <th>Day</th>
                                  <th>Stadium</th>
                                  <th>Compitation</th>
                                  <th>price</th>
                                  <th>quntity</th>
                                  <th>Action</th>
                               </tr>
                           </thead>
                           <tbody>
                            @foreach ($matches as $matche)
                                <tr>
                                    <td class="text-center"><img class="rounded img-fluid avatar-40" src="{{ asset('storage/'.$matche->ticket_image) }}" alt="profile"></td>
                                    <td><b>{{ $matche->first_team_en }}</b><br>/<b>{{ $matche->seconed_team_en }}</b></td>
                                    <td>{{ $matche->day }}<br>{{ $matche->time }}</td>
                                    <td>{{ $matche->stadium_en }}</td>
                                    <td><span class="badge iq-bg-primary">{{ $matche->compitation_en }}</span></td>
                                    <td>{{ $matche->price }}</td>
                                    <td>{{ $matche->quantity }}</td>
                                    <td>
                                        <div class="flex align-items-center list-user-action">

                                            <a class="iq-bg-primary" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" href="{{ route('dashboard.matches.edit',$matche->id) }}"><i class="ri-pencil-line"></i></a>
                                            <form method="POST" action="{{ route('dashboard.matches.destroy',$matche->id) }}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-link" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="ri-delete-bin-line"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                           </tbody>
                         </table>
                      </div>
                         {{ $matches->withQueryString()->links() }}
                   </div>
                </div>
          </div>
       </div>
    </div>
 </div>
@endsection
