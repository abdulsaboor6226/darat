@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    {{-- <link href="https://www.ihbc.org.uk/consultationsdb_new/media/css/jquery.dataTables.css" rel="stylesheet" type="text/css" /> --}}
    <style>

    </style>
    <style>


    </style>
@endsection
@extends('layouts.master')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row">
                    <div class="col">
                        {{-- <h4 class="page-title">Members List</h4> --}}
                        {{-- <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dastone</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Tables</a></li>
                                <li class="breadcrumb-item active">Datatables</li>
                            </ol> --}}
                    </div>
                    <!--end col-->

                </div>
                <!--end row-->
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
    <!-- end page title end breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $title }} List</h4>
                    {{-- <a class=" btn btn-sm btn-soft-primary float-end" href="{{$url.'/create'}}" role="button"><i class="fas fa-plus me-2"></i>New {{$title}}</a> --}}

                    {{-- <p class="text-muted mb-0">
                        </p>

                            <div class="nav-link float-end">
                            </div> --}}

                </div>
                <!--end card-header-->
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="myTable" class="table table-bordered table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Facebook Connects</th>
                                        <th>No of Projects</th>
                                        <th>Device</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection


@section('scripts')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.colVis.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/') }}assets/pages/jquery.datatable.init.js"></script>
    <script>
        $(function () {
                // datatable-buttons
                var table = $('#myTable').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    ajax: {
                        url: '{{$url}}',
                        dataType: "json",
                        type: "GET",

                    },
                    columns: [
                        {data: 'id'},
                        {data: 'name'},
                        {data: 'email'},
                        {data: 'phone'},
                        {data: 'facebook_connects'},
                        {data: 'no_of_projects'},
                        {data: 'device'},
                        {data: 'actions'}
                    ],
                    // dom: 'lBfrtip',
                    // buttons: datatable_buttons,
                    ordering: true,
                });
    })
    </script>
@endsection
