@section('css')
    <!-- DataTables -->
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{asset('assets/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    {{-- <link href="https://www.ihbc.org.uk/consultationsdb_new/media/css/jquery.dataTables.css" rel="stylesheet" type="text/css" /> --}}
    <style>

    </style>
<style>


</style>
@endsection
@extends('layouts.master')

@section('content')

        <!-- Project Moderate -->
        <div class="row mt-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> Moderate {{$title}} List</h4>
                    </div><!--end card-header-->
                    <div class="card-body">

                        <div class="table-rep-plugin">
                            <div class="table-responsive mb-0" data-pattern="priority-columns">
                                <table id="myTable2" class="table table-bordered table-striped mb-0">
                                    <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Project</th>
                                        <th>Location</th>
                                        <th>City</th>
                                        <th>Developed BY</th>
                                        <th>Developer's Contact</th>
                                        <th>Approved</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end  Project Moderate -->


        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        {{-- <div class="col">
                            <h4 class="page-title">{{$title}} List</h4>
                        <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Projects</li>
                            </ol>
                        </div> --}}

                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="nav-tabs-custom text-center">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link text-center active" type='all' data-bs-toggle="tab" href="#project" role="tab"><i class="la la-tasks d-block"></i>All</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center" type='plot' data-bs-toggle="tab" href="#plot" role="tab"><i class="la la-building d-block"></i>Plot</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center" type='building' data-bs-toggle="tab" href="#building" role="tab"><i class="la la-building  d-block"></i>Building</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center" type='vila' data-bs-toggle="tab" href="#vila" role="tab"><i class="la la-building  d-block"></i>Vila</a>
                        </li>
                    </ul>
                </div>
                <hr>
                <div class="card">
                     <div class="card-header">
                        <h4 class="card-title">{{$title}} List</h4>
                        <a class=" btn btn-sm btn-soft-primary float-end" href="{{$url.'/create'}}" role="button"><i class="fas fa-plus me-2"></i>New {{$title}}</a>

                    </div><!--end card-header-->
                    <div class="card-body">

                        <div class="table-rep-plugin">
                            <div class="table-responsive mb-0" data-pattern="priority-columns">
                                <table id="myTable" class="table table-bordered table-striped mb-0">
                                    <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Project Name</th>
                                        <th>Location</th>
                                        <th>City</th>
                                        <th>Description</th>
                                        <th>Developed BY</th>
                                        <th>Developer's Contact</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row -->
@endsection


@section('scripts')
<script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
 <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap5.min.js')}}"></script>
 <!-- Buttons examples -->
 <script src="{{asset('assets/plugins/datatables/dataTables.buttons.min.js')}}"></script>
 <script src="{{asset('assets/plugins/datatables/buttons.bootstrap5.min.js')}}"></script>
 <script src="{{asset('assets/plugins/datatables/jszip.min.js')}}"></script>
 <script src="{{asset('assets/plugins/datatables/pdfmake.min.js')}}"></script>
 <script src="{{asset('assets/plugins/datatables/vfs_fonts.js')}}"></script>
 <script src="{{asset('assets/plugins/datatables/buttons.html5.min.js')}}"></script>
 <script src="{{asset('assets/plugins/datatables/buttons.print.min.js')}}"></script>
 <script src="{{asset('assets/plugins/datatables/buttons.colVis.min.js')}}"></script>
 <!-- Responsive examples -->
 <script src="{{asset('assets/plugins/datatables/dataTables.responsive.min.js')}}"></script>
 <script src="{{asset('assets/plugins/datatables/responsive.bootstrap4.min.js')}}"></script>
 <script src="{{asset('/')}}assets/pages/jquery.datatable.init.js"></script>


 <script>
     let type='';
  
    $(function () {
                // datatable-buttons
                var table = $('#myTable').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    ajax: {
                        url: '{{$url}}?type='+type,
                        dataType: "json",
                        type: "GET",

                    },
                    columns: [
                        {
                            data: 'logo',
                            "render": function (data) {
                                return '<img style="width: 70px;" src="{{$project_url}}/' + data + '">';
                            }
                        },
                        {data: 'name'},
                        {data: 'location'},
                        {data: 'city'},
                        {data: 'description'},
                        {data: 'developed_by'},
                        {data: 'developer_contact'},
                        {data: 'actions'}
                    ],
                    // dom: 'lBfrtip',
                    // buttons: datatable_buttons,
                    ordering: true,
                });
    })
 $('.nav-link').on('click',function(){
         type=$(this).attr('type');
         myFunction();
    })
    $(function () {
                // datatable-buttons
                var table = $('#myTable2').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    ajax: {
                        url: '{{$url2}}',
                        dataType: "json",
                        type: "GET",

                    },
                    columns: [
                        {
                            data: 'logo',
                            "render": function (data) {
                                return '<img style="width: 70px;" src="{{$project_url}}/' + data + '">';
                            }
                        },
                        {data: 'name'},
                        {data: 'location'},
                        {data: 'city'},
                        {data: 'developed_by'},
                        {data: 'developer_contact'},
                        {data: 'actions'}
                    ],
                    // dom: 'lBfrtip',
                    // buttons: datatable_buttons,
                    ordering: true,
                });
    })

    function isApproved(e, id) {
            let url2 = '{{url("is-approved")}}/' + id;
            if (e.target.value == 0) {
                $.ajax({
                        type: 'post',
                        url: url2,
                        data:{is_approved: e.target.value},
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (r) {
                            toastAlert('Success Message', r.message, 'success')
                            location.reload()
                        },
                        
                    })
            } else {
               
                $.ajax({
                        type: 'post',
                        url: url2,
                        data:{is_approved: e.target.value},
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (r) {
                            toastAlert('Success Message', r.message, 'success')
                            location.reload()
                        },
                 })
            }
    }
 </script>
@endsection



