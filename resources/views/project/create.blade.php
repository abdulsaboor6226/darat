@section('css')
    <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/image-uploader/image-uploader.min.css') }}" rel="stylesheet">

    <!-- DataTables -->
    <style>
        .bg-info {
            background-color: #17a2b8 !important;
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

                </div>
                <!--end row-->
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
    <div class="row">

        <div class="col-lg-12">
            <form action="{{ url('projects') }}" method="POST" class="form-parsley" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $title }}</h4>
                    </div>
                    <!--end card-header-->
                    <div class="card-body">

                        <div class="tab-content">
                            <div class="tab-pane p-3 active">
                                <div class="row">
                                    <div class="col-4">
                                        <label class="my-3">Logo</label>
                                        <div class="form-group">
                                            <input type="file" class="dropify form-control" name="logo" required>
                                        </div>
                                    </div>
                                    <div class="col-4" style="margin-top: 10%">
                                        <label class="my-3">Project</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" value="{{ old('name') }}"
                                                name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-4" style="margin-top: 10%">
                                        <label class="my-3">Location</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" value="{{ old('location') }}"
                                                name="location" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label class="my-3">City</label>
                                        <div class="form-group">
                                            <select name="city_id" class="select2 mb-3 select2" style="width: 100%">
                                                <option value="">Select City</option>
                                                @foreach (App\Models\City::all() as $city)
                                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label class="my-3">Developed By</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" value="{{ old('developed_by') }}"
                                                name="developed_by" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="my-3">Developer's Contact</label>
                                            <div class="form-group">
                                                <input type="number" class="form-control"
                                                    value="{{ old('developer_contact') }}" name="developer_contact"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="my-3">Upload File (*pdf)</label>
                                            <div class="form-group">
                                                <input type="file" class="form-control"
                                                name="pdf_file" required >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="my-3">Description</label>
                                    <textarea type="text" cols="5" rows="5" class="form-control" name="description" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="my-3">Is Featured</label><br>
                                    <label class="switch ">
                                        <input type="checkbox" onchange="featured (event)" id="is_featured"
                                            name="is_featured" class="success">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="row" id="featured" style="display: none">
                                    <div class="col-4">
                                        <label class="my-3">Featured Whatsapp Number</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" 
                                            id="featured_whatsapp_number"
                                                value="{{ old('featured_whatsapp_number') }}"
                                                name="featured_whatsapp_number" >
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label class="my-3">Featured Phone Number</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="featured_phone_number"
                                                value="{{ old('featured_phone_number') }}" name="featured_phone_number"
                                                >
                                        </div>
                                    </div>

                                </div>
                                <hr>
                                <label class="form-label">Photos</label>
                                <div class="project-images" style="padding-top: .5rem;"></div>

                                <div class="form-group">
                                    <button type="submit" class="float-end btn btn-primary">Submit</button>
                                    <a href="{{ $url }}" class="float-end btn btn-danger">Back</a>
                                    {{-- <button type="button" data-bs-toggle="tab" href="#plot" role="tab" class=" ">Next</button> --}}
                                </div>


                            </div>

                        </div>
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->

            </form>

        </div>
        <!--end col-->

    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/repeater/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('assets/pages/jquery.form-repeater.js') }}"></script>
    <script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/pages/jquery.form-upload.init.js') }}"></script>
    <script src="{{ asset('assets/image-uploader/image-uploader.min.js') }}"></script>
    <script>
        $('.project-images').imageUploader();
        $('.building-images').imageUploader();
        $('.villa-images').imageUploader();

        function featured(e) {
            let check = $('#is_featured').is(":checked");
            if (check == true) {
                $("#featured").show();
                $("#featured_whatsapp_number").attr("required", true);
                 $("#featured_phone_number").attr("required", true);
                
            } else {

                $("#featured").hide();
                $("#featured_whatsapp_number").attr("required", false);
                $("#featured_phone_number").attr("required", false);

            }
        }
    </script>
@endsection
