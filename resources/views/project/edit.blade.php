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
            <form action="{{ $url . '/' . $project->id }}" method="POST" class="form-parsley"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title ">{{ $title }}</h4>
                    </div>
                    <!--end card-header-->
                    <div class="card-body">
                        <div class="row">
                            @foreach ($project->products as $item)
                                <div class="col-3">
                                    <div class="card">
                                        <div class="card-header bg-soft-success">
                                            <h4 class="card-title ">Products</h4>
                                            <p class="text-muted mb-0">

                                            </p>
                                        </div>
                                        <!--end card-header-->
                                        <div class="card-body">
                                            <div class="media">
                                                <img src="{{ $product_url . '/' . $item->getItemImage() }}" alt="" style="height: 60px;
                                                                                                        width: 69px;"
                                                    class="rounded-circle">
                                                <div class="media-body align-self-center ms-3 text-truncate">
                                                    <h3 class="my-0 fw-bold">{{ $item->title }}</h3>
                                                    <p class="text-muted mb-2 font-13">
                                                        {{ $item->size . '  ' . $item->size_name }}</p>
                                                    <a href='{{ url('products/' . $item->id . '/edit') }}'
                                                        title='Edit Record'><i data-feather='list' class='fas fa-pen me-2''></i></a>
                                                                                                            <a  href='
                                                            javascript:' title='Delete Record'
                                                            onclick="deleteRecordAjax({{ $delete_url }})"
                                                            class='danger p-0'><i
                                                                class='fas fa-trash me-2''></i></a>
                                                                                                        </div><!--end media-body-->
                                                                                                    </div>
                                                                                                </div><!--end card-body-->
                                                                                            </div>
                                                                                        </div>
    @endforeach
                                                                                        <!--end card-->
                                                                                    </div>
                                                                                    <hr>
                                                                                    <div class="tab-content">
                                                                                        <div  class="tab-pane p-3 active" >
                                                                                            <div class="row">
                                                                                                <div class="col-4">
                                                                                                    <label class="my-3">Logo</label>
                                                                                                    <div class="form-group">
                                                                                                        <input type="file" class="dropify form-control" data-default-file="{{ $project_url . '/' . $project->logo }}"    name="logo" >
                                                                                                     </div>
                                                                                                </div>
                                                                                                <div class="col-4" style="margin-top: 10%">
                                                                                                    <label class="my-3">Project</label>
                                                                                                    <div class="form-group">
                                                                                                        <input type="text"  class="form-control" value="{{ isset($project->name) ? $project->name : old('name') }}" name="name" required>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-4"  style="margin-top: 10%">
                                                                                                    <label class="my-3">Location</label>
                                                                                                    <div class="form-group">
                                                                                                        <input type="text" class="form-control" value="{{ isset($project->location) ? $project->location : old('location') }}" name="location" required>
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
    <option value="{{ $city->id }}" @if ($city->id == $project->city_id) selected @endif>
                                                                                                                {{ $city->name }}
                                                                                                            </option>
    @endforeach

                                                                                                        </select>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-4">
                                                                                                    <label class="my-3">Developed By</label>
                                                                                                    <div class="form-group">
                                                                                                        <input type="text" class="form-control" value="{{ $project->developed_by }}" name="developed_by" required>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-4">
                                                                                                    <div class="form-group">
                                                                                                        <label class="my-3">Developer'
                                                                s Contact</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $project->developer_contact }}"
                                                                        name="developer_contact" required>
                                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label class="my-3">Upload File (*pdf)</label>
                                                    <div class="form-group">
                                                        <input type="file" class="form-control"
                                                     name="pdf_file">
                                                    </div>
                                                    <embed src="{{ $project_url . '/' . $project->pdf_file }}"
                                                        width="500" height="375" type="application/pdf">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="my-3">Description</label>
                                            <textarea type="text" cols="5" rows="5" class="form-control" name="description"
                                                required>{{ $project->description }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="my-3">Is Featured</label>
                                            <br>
                                            <label class="switch">
                                                <input type="checkbox" onchange="featured (event)" name="is_featured"
                                                    id="is_featured" @if ($project->is_featured == true) checked @endif
                                                    class="success">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                            <div class="row" id="featured" style="display: none">
                                                <div class="col-6">
                                                    <label class="my-3">Featured Whatsapp Number</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"
                                                            value="{{ $project->featured_whatsapp_number }}"
                                                            name="featured_whatsapp_number">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label class="my-3">Featured Phone Number</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"
                                                            value="{{ $project->featured_phone_number }}"
                                                            name="featured_phone_number">
                                                    </div>
                                                </div>

                                            </div>
                                        <hr>
                                        <label class="form-label">Photos</label>
                                        <div class="project-images" style="padding-top: .5rem;"></div>
                                        <input type="text" hidden name="my_image[]" value="{{ $images }}"
                                            id="my_image" multiple>
                                        <div class="form-group">
                                            <button type="submit" class="float-end btn btn-primary">Submit</button>
                                            <a href="{{ $url }}" class="float-end btn btn-danger">Back</a>
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
        $(document).ready(function(){
            let check2 = @json($project->is_featured);
            if (check2 == true) {
                $("#featured").show();
            } else {

                $("#featured").hide();

            }
        });
        // $('.plot-images').imageUploader();
        //     let preloaded = [
        //     {id: 1, src: 'https://picsum.photos/500/500?random=1'},
        //     {id: 2, src: 'https://picsum.photos/500/500?random=2'},

        // ];
        var data = {!! json_encode($images, JSON_HEX_TAG) !!};
        var url = {!! json_encode($project_url, JSON_HEX_TAG) !!};
        var ar = [];
        $.each(data, function(index, value) {
            let obj = {
                id: value.id,
                src: `${url+'/'+value.image}`
            }
            ar.push(obj);
        });
        var imag = $('#project-images').val() == ar

        $('.project-images').imageUploader({
            preloaded: ar,
            imagesInputName: 'images',
            preloadedInputName: 'old',
            maxSize: 2 * 1024 * 1024,
            maxFiles: 10
        });

        function featured(e) {
            let check = $('#is_featured').is(":checked");
            if (check == true) {
                $("#featured").show();
            } else {

                $("#featured").hide();

            }
        }
    </script>
@endsection
