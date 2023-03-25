@section('css')
    <!-- DataTables -->
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{asset('assets/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    {{-- <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" /> --}}

    {{-- <link href="https://www.ihbc.org.uk/consultationsdb_new/media/css/jquery.dataTables.css" rel="stylesheet" type="text/css" /> --}}
    <style>
    td.dt-control {
        background: url(https://www.datatables.net/examples/resources/details_open.png) no-repeat center center;
        cursor: pointer;
    }
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

                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->
        <div class="row">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8 offset-lg-1 text-center">

                                    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach($product->installmentImages as $slider)
                                            @if ($loop->first)
                                            <div class="carousel-item active">
                                                <img style=" width:80% ; height:300px" src="{{$product_url.'/'.$slider->image}}" class="d-block w-100" alt="...">
                                            </div>
                                            @else
                                            <div class="carousel-item">
                                                <img style=" width:80% ; height:300px" src="{{$product_url.'/'.$slider->image}}" class="d-block w-100" alt="...">
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div><!--end col-->
                            </div><!--end row-->
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->
            </div>
            <div class="col-12">
                
                <div class="card">
                     <div class="card-header">
                        <h4 class="card-title">Intallment Plan List</h4>

                        <a class=" btn btn-sm btn-soft-danger float-end " href="{{url('projects/'.$product->project_id)}}" role="button"><i class=" me-3"></i>Back</a>

                    </div><!--end card-header-->
                    <div class="card-body">

                        <div class="table-rep-plugin">
                            <div class="table-responsive mb-0" data-pattern="priority-columns">
                                <table id="myTable" class="table table-bordered table-striped mb-0">
                                    <thead>
                                    <tr>
                                    
                                        <th>Title</th>
                                        <th>Amount</th>
                                        <th>Time</th>
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
     
      $(function () {
        // datatable-buttons
        var table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            ajax: {
                url: '{{$url}}/'+'{{$id}}',
                dataType: "json",
                type: "GET",

            },
            columns: [
                {data: 'title'},
                {data: 'amount'},
                {data: 'time'},
            ],
            // dom: 'lBfrtip',
            // buttons: datatable_buttons,
            ordering: true,
        });
            
    });

 </script>
@endsection



