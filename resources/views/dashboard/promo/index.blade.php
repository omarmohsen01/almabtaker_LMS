@extends('layouts.master')
@section('content')
<div id="content-page" class="content-page">
    <div class="container-fluid">
       <div class="row">
          <div class="col-sm-12">
                <div class="iq-card">
                   <div class="iq-card-header d-flex justify-content-between">
                      <div class="iq-header-title">
                         <h4 class="card-title">Promo Code List</h4>
                      </div>
                   </div>
                   <x-dashboard.alert />
                   <div class="iq-card-body">
                      <div class="table-responsive">
                        <a class="btn btn-primary" style="margin-bottom: 15px" href="{{ route('dashboard.promo.create') }}">Add New Promo Code</a>
                         <table id="user-list-table" class="table table-striped table-bordered mt-4" role="grid" aria-describedby="user-list-page-info">
                           <thead>
                               <tr>
                                  <th>Code</th>
                                  <th>Expire At</th>
                                  <th>Discount</th>
                                  <th>Action</th>
                               </tr>
                           </thead>
                           <tbody>
                            @foreach ($promos as $promo)
                                <tr>
                                    <td><b>{{ $promo->code }}</b></td>
                                    <td>{{ $promo->expire_data }}</td>
                                    <td>{{ $promo->discount }}</td>
                                    <td>
                                        <div class="flex align-items-center list-user-action">
                                            <form method="POST" action="{{ route('dashboard.promo.destroy',$promo->id) }}">
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
                         {{-- <div class="row justify-content-between mt-3">
                            <div id="user-list-page-info" class="col-md-6">
                               <span>Showing 1 to 5 of 5 entries</span>
                            </div>
                            <div class="col-md-6">
                               <nav aria-label="Page navigation example">
                                  <ul class="pagination justify-content-end mb-0">
                                     <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                     </li>
                                     <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                     <li class="page-item"><a class="page-link" href="#">2</a></li>
                                     <li class="page-item"><a class="page-link" href="#">3</a></li>
                                     <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                     </li>
                                  </ul>
                               </nav>
                            </div>
                         </div> --}}
                         {{ $promos->withQueryString()->links() }}
                   </div>
                </div>
          </div>
       </div>
    </div>
 </div>
@endsection
