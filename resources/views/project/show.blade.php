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
            <div class="col-12">
                <div class="card mb-3">
                    <div class="row no-gutters">
                        <div class="col-md-2 align-self-center text-center">
                            <img  height="80" src="{{env('APP_IMAGE_URL').'project/'.$project->logo}}" alt="Card image">
                        </div>
                        <div class="col-md-10">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h4 class="card-title">Project: <span class="badge badge-outline-dark">{{$project->name}}</span></h4>
                                    </div>
                                    <div class="col">
                                        <h4 class="card-title">Location: <span class="badge badge-outline-dark">{{$project->location}}</span>   </h4>
                                    </div>
                                    <div class="col">
                                        <h4 class="card-title">City: <span class="badge badge-outline-dark">{{$project->city->name}}</span>   </h4>
                                    </div>

                                    <div class="col-auto">
                                        <h4 class="card-title">DeveloperContact: <span class="badge badge-outline-dark">{{$project->developer_contact}}</span>   </h4>
                                    </div>
                                    <!--<div class="col-auto">-->
                                    <!--    <h4 class="card-title" id="title_name">  Is Featured:-->
                                    <!--    <label class="switch ">-->
                                    <!--        <input type="checkbox" onchange="isFeatured (event,{{$project->id}})"-->
                                    <!--               name="is_featured" value="{{$project->is_featured}}"-->
                                    <!--               @if($project->is_featured == true) checked-->
                                    <!--               @endif   class="success">-->
                                    <!--        <span class="slider round"></span>-->
                                    <!--    </label>-->
                                    <!--    </h4>-->
                                    <!--</div>-->

                                </div>  <!--end row-->
                            </div><!--end card-header-->
                            <div class="card-body">
                                <h4 class="card-title">Description:</h4>
                                <p class="card-text">{{$project->description}}.</p>
                            </div><!--end card-body-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-8 offset-lg-1 text-center">

                                        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                @foreach($project->projectImages as $slider)
                                                @if ($loop->first)
                                                <div class="carousel-item active">
                                                    <img style=" width:80% ; height:300px" src="{{$project_url.'/'.$slider->image}}" class="d-block w-100" alt="...">
                                                </div>
                                                @else
                                                <div class="carousel-item">
                                                    <img style=" width:80% ; height:300px" src="{{$project_url.'/'.$slider->image}}" class="d-block w-100" alt="...">
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
                <div class="card">
                     <div class="card-header">
                        <h4 class="card-title">Product List</h4>

                        <a class=" btn btn-sm btn-soft-danger float-end " href="{{$url}}" role="button"><i class=" me-3"></i>Back</a>

                    </div><!--end card-header-->
                    <div class="card-body">

                        <div class="table-rep-plugin">
                            <div class="table-responsive mb-0" data-pattern="priority-columns">
                                <table id="myTable" class="table table-bordered table-striped mb-0">
                                    <thead>
                                    <tr>
                                        {{-- <th></th> --}}
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Size</th>
                                        <th>Product Type</th>
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
     /* Formatting function for row details - modify as you need */
        // function format ( d ) {
        //     // `d` is the original data object for the row
        //     return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        //         '<tr>'+
        //             '<td></td>'+
        //         '</tr>'+
        //         '<tr>'+
        //             '<td>Full name:</td>'+
        //             '<td>'+d.name+'</td>'+
        //         '</tr>'+
        //         '<tr>'+
        //             '<td>Extension number:</td>'+
        //             '<td>'+d.extn+'</td>'+
        //         '</tr>'+
        //         '<tr>'+
        //             '<td>Extra info:</td>'+
        //             '<td>And any further details here (images etc)...</td>'+
        //         '</tr>'+
        //     '</table>';
        // }
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
                // {
                // "className":      'dt-control',
                // "orderable":      false,
                // "data":           null,
                // "defaultContent": ''
                // },
                {
                    data: 'image',
                    "render": function (data) {
                    return '<img style="width: 70px;" src="{{$product_url}}/' + data + '">';
                    }
                },
                {data: 'title'},
                {data: 'size'},
                {data: 'product_type'},
                {data: 'actions'},
            ],
            // dom: 'lBfrtip',
            // buttons: datatable_buttons,
            ordering: true,
        });
          
    });
    function isFeatured(e, id) {
            let url2 = '{{url("is-featured")}}/' + id;
            if (e.target.value == 0) {
                $.ajax({
                        type: 'post',
                        url: url2,
                        data:{is_featured: e.target.value},
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
                        data:{is_featured: e.target.value},
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



