@section('css')
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
                       <div class="col">
                            <h4 class="page-title">{{$title}} Update</h4>
                            
                        </div>

                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row">
            
            <div class="col-lg-12">
                <form action="{{$url.'/'.$rate_list->id}}" method="post" class="form-parsley" enctype="multipart/form-data">
                    @csrf
                    @method('Put')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{$title}}</h4>
                    </div><!--end card-header-->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <label class="my-3">Project</label>
                                <div class="form-group"> 
                                    <select name="project_id" class="select2 mb-3 select2" style="width: 100%" required>
                                        <option value="">select project</option>
                                      @foreach ($projects as $project)
                                          <option value="{{$project->id}}" @if($rate_list->project_id == $project->id) selected @endif>{{$project->name}}</option>
                                      @endforeach
                                    </select>                                           
                                   
                                </div>
                            </div>
                            <div class="col-3">
                                <label class="my-3">City</label>
                                <div class="form-group">                                            
                                    <select name="city_id" class="select2 mb-3 select2" style="width: 100%" required>
                                        <option value="">select city</option>
                                         @foreach ($cities as $city)
                                        <option value="{{$city->id}}" @if($rate_list->city_id == $city->id) selected @endif>{{$city->name}}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <label class="my-3">Division</label>
                                <div class="form-group">                                           
                                    <select name="division" id="division" class="select2 mb-3 select2" style="width: 100%" required>
                                        <option value="Precinct" @if($rate_list->division == 'Precinct') selected @endif >Precinct</option>
                                        <option value="Phase" @if($rate_list->division == 'Phase') selected @endif >Phase</option>
                                        <option value="Sector" @if($rate_list->division == 'Sector') selected @endif>Sector</option>
                                    </select>
                                </div>
                            </div>
                         </div>             
                         <div class="form-group">   
                            <label class="my-3">Description</label>
                                <textarea type="text" cols="2" rows="3" class="form-control" value="{{old('description')}}" name="description" required>{{$rate_list->desc}}</textarea>
                        </div>
                        <hr>
                        <fieldset>
                            <div class="repeater-default">
                                <?php
                                    $i = 0;
                                     ?>
                                <div data-repeater-list="rate_list">
                                    @foreach($rate_list->rateListDetails as $detail)

                                    <div data-repeater-item="">

                                        <div class="form-group row d-flex align-items-end">

                                            <div class="col-sm-2">
                                                <label class="form-label" id='division_name'>{{$rate_list->division}}</label>
                                                <input type="text" name="rate_list[{{$i}}][name]"  value="{{$detail->name}}" class="form-control" required>
                                            </div><!--end col-->
                                            <div class="col-sm-2">
                                                <label class="form-label">Size</label>
                                                <input type="text" name="rate_list[{{$i}}][size]"  value="{{$detail->size}}" class="form-control" required>
                                            </div><!--end col-->
                                            <div class="col-sm-2">
                                                <label class="form-label">Marla</label>
                                                <select name="rate_list[{{$i}}][size_name]" class=" form-control" style="width: 100%" >
                                                    <option value="Marla" @if($detail->size_name == 'Marla') selected @endif >Marla</option>
                                                    <option value="Square Feet" @if($detail->size_name == 'Square Feet') selected @endif >Square Feet</option>
                                                    <option value="Square Yard" @if($detail->size_name == 'Square Yard') selected @endif >Square Yard</option>
                                                    <option value="Square Meters" @if($detail->size_name == 'Square Meters') selected @endif >Square Meters</option>
                                                    <option value="Kanal" @if($detail->size_name == 'Kanal') selected @endif >Kanal</option>
                                                </select>
                                            </div><!--end col-->
                                            <div class="col-sm-2">
                                                <label class="form-label">Min Price</label>
                                                <input type="number" name="rate_list[{{$i}}][min_price]" value="{{$detail->min_price}}"  class="form-control" required>
                                            </div><!--end col-->
                                            <div class="col-sm-2">
                                                <label class="form-label">Max Price</label>
                                                <input type="number" name="rate_list[{{$i}}][max_price]" value="{{$detail->max_price}}" class="form-control" required>
                                            </div><!--end col-->
                                            
                                            <div class="col-sm-2">
                                                <span data-repeater-delete="" class="btn btn-outline-danger">
                                                    <span class="far fa-trash-alt me-1"></span> Delete
                                                </span>
                                            </div><!--end col-->
                                           
                                        </div>
                                       
                                    </div>
                                    <?php
                                    $i++;
                                       ?>
                                    @endforeach 
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
                        </div><!--end card-body-->
                        
                </div><!--end card-->
                <button type="submit" class="btn float-end btn-primary">Update</button>
                <a href="{{$url}}" class="btn float-end btn-danger">Back</a>
            </form>

            </div><!--end col-->
           
     </div>
@endsection
@section('scripts')
<script src="{{asset("assets/plugins/repeater/jquery.repeater.min.js")}}"></script>
        <script src="{{asset("assets/pages/jquery.form-repeater.js")}}"></script>
        <script src="{{asset("assets/image-uploader/image-uploader.min.js")}}"></script> 
        <script>
            $('.input-images-1').imageUploader();
            $('#division').on('change',function(){
                $('#division_name').text()
                $('#division_name').text($(this).val())
            })
            </script>   
@endsection





