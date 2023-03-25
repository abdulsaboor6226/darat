@section('css')
    <!-- DataTables -->
    <link href="../plugins/datatables/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="../plugins/datatables/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="../plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <style>
        .bg-info {
            background-color: #17a2b8!important;
        }
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
                            <h4 class="page-title">{{$title}} Update</h4>
                        </div>

                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row">

            <div class="col-lg-12">
                <form action="{{$url.'/'.$package->id}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('Put')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{$title}}</h4>
                    </div><!--end card-header-->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <label class="my-3">Title</label>
                                <div class="input-group">
                                    <input type="text" class="form-control"  value="{{$package->title}}" name="title">
                                <span class="input-group-text"><i class="ti ti-text font-16"></i></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="my-3">Price</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" value="{{$package->price}}" name="price">
                                    <span class="input-group-text"><i class="ti ti-text font-16"></i></span>

                                </div>
                            </div>
                        </div>

                        <label class="my-3">Description</label>
                        <div class="input-group">
                            <textarea type="text" cols="5" rows="5" class="form-control" name="description">{{$package->desc}}</textarea>
                            <span class="input-group-text"><i class="ti ti-text font-16"></i></span>
                        </div>


                    </div><!--end card-body-->
                </div><!--end card-->
                <button type="submit" class="float-end btn btn-primary">Update</button>
                <a href="{{$url}}" class="float-end btn btn-danger">Back</a>
            </form>

            </div><!--end col-->

                                </div>
@endsection






