@section('css')
<link href="{{asset("assets/plugins/dropify/css/dropify.min.css")}}" rel="stylesheet">
<link href="{{asset("assets/image-uploader/image-uploader.min.css")}}" rel="stylesheet">

    <!-- DataTables -->
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
                        {{-- <div class="col">
                            <h4 class="page-title">{{$title}} Create</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item "><a href="{{$url}}">{{$title}}</a></li>
                                <li class="breadcrumb-item active">{{$title.'Create'}}</li>
                            </ol>
                        </div><!--end col--> --}}

                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row">

            <div class="col-lg-12">
            <form action="{{url('products')}}" method="POST" class="form-parsley" enctype="multipart/form-data">
                    @csrf
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{$title}}</h4>
                    </div><!--end card-header-->
                    <div class="card-body">


                        <div class="tab-content">

                            <div  class="tab-pane p-3 active" >
                                <div>
                                    <h3 style="text-align: center">Product</h3>
                                    <hr>

                                    <div class="form-group row d-flex align-items-end" >
                                            <div class="col-6">
                                                <label class="form-label">Project</label>
                                                <select name="project_id" required class="select2 mb-3 select2" style="width: 100%" required>
                                                    <option value="">select project</option>
                                                  @foreach ($projects as $project)
                                                      <option value="{{$project->id}}">{{$project->name}}</option>
                                                      @endforeach
                                                </select>
                                            </div><!--end col-->
                                            <div class="col-6">
                                                <label class="form-label">Product Type</label>
                                                <select name="product_type" required class="select2 mb-3 select2" style="width: 100%">
                                                    <option value="Plot">Plot</option>
                                                    <option value="Shop">Shop</option>
                                                    <option value="Flat">Flat</option>
                                                    <option value="Villa">Villa</option>
                                                </select>
                                            </div><!--end col-->

                                            {{-- <input type="radio" class="btn-check" name="plot_check"  id="plot_check" autocomplete="off">
                                            <label class="btn btn-outline-secondary" for="plot_check">Plot</label> --}}
                                    </div>
                                    <div class="form-group row d-flex align-items-end">
                                        <div class="col-sm-4">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="title" required  class="form-control">
                                        </div><!--end col-->
                                        <div class="col-sm-4">
                                            <label class="form-label">Size</label>
                                            <input type="text" name="size"  required class="form-control">
                                        </div><!--end col-->
                                        <div class="col-sm-4">
                                            <label class="form-label">Marla</label>
                                            <select name="size_name" required class="select2 mb-3 select2" style="width: 100%">
                                                <option value="Marla">Marla</option>
                                                <option value="Square Feet">Square Feet</option>
                                                <option value="Square Yard">Square Yard</option>
                                                <option value="Square Meters">Square Meters</option>
                                                <option value="Kanal">Kanal</option>
                                            </select>
                                        </div><!--end col-->
                                        
                                    </div>
                                </div>
                                <hr>

                                    <fieldset>
                                        <div class="repeater-default">
                                            <h3 style="text-align: center">Installment Plan</h3>
                                            <div data-repeater-list="plot">
                                                <div data-repeater-item="">
                                                    <div class="form-group row d-flex align-items-end">
                                                        <div class="col-sm-4">
                                                            <label class="form-label">Title</label>
                                                            <input type="text" required name="plot[][title]"  class="form-control">
                                                        </div><!--end col-->
                                                        <div class="col-sm-4">
                                                            <label class="form-label">Amount</label>
                                                            <input type="number" required name="plot[][amount]"  class="form-control">
                                                        </div><!--end col-->
                                                        <div class="col-sm-2">
                                                            <label class="form-label">Time</label>
                                                            <input type="text" required name="plot[][time]" class="form-control">
                                                        </div><!--end col-->
                                                        <div class="col-sm-2">
                                                            <span data-repeater-delete="" class="btn btn-outline-danger">
                                                                <span class="far fa-trash-alt me-1"></span> Delete
                                                            </span>
                                                        </div><!--end col-->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0 row">
                                                <div class="col-sm-12">
                                                    <span data-repeater-create="" class="btn btn-outline-secondary">
                                                        <span class="fas fa-plus"></span> Add
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <hr>
                                        <label class="form-label">Photos</label>
                                        @error('images')
                                            <small class="text-danger">{{$message}}</small>
                                         @enderror
                                        <div class="plot-images" style="padding-top: .5rem;"></div>
                                        
                                        <hr>
                                        <button type="submit" class=" float-end btn btn-primary">Submit</button>
                                        <a href="{{url('projects')}}" class="float-end btn btn-danger">Back</a>

                            </div>
                        </div>
                        </div><!--end card-body-->
                </div><!--end card-->

            </form>

            </div><!--end col-->

                                </div>
@endsection
@section('scripts')
<script src="{{asset("assets/plugins/repeater/jquery.repeater.min.js")}}"></script>
        <script src="{{asset("assets/pages/jquery.form-repeater.js")}}"></script>
        <script src="{{asset("assets/plugins/dropify/js/dropify.min.js")}}"></script>
        <script src="{{asset("assets/pages/jquery.form-upload.init.js")}}"></script>
        <script src="{{asset("assets/image-uploader/image-uploader.min.js")}}"></script>

        <script>
            $('.plot-images').imageUploader();
            $('.building-images').imageUploader();
            $('.villa-images').imageUploader();
            </script>
@endsection





