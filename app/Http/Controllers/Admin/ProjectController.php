<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Auth;
use Alert;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Models\InstallmentImages;
use App\Models\Product;
use App\Models\ProductInstallment;
use App\Models\Project;
use App\Models\User;
use App\Models\ProjectImages;
use Carbon\Carbon;

class ProjectController extends Controller
{
    const TITLE = 'Project';
    const VIEW = 'project';
    const URL = 'projects';
    const URL2 = 'modrate-projects';

    // const IMAGE_SRC = 'images/members/';

    public function __construct()
    {

        view()->share([
            'url' => url(self::URL),
            'url2' => url(self::URL2),
            'title' => self::TITLE,
            'project_url' => env('APP_IMAGE_URL') . 'project',
            'product_url' => env('APP_IMAGE_URL') . 'product',
        ]);
        ini_set('upload_max_filesize', "4000M");
    }

    public function toString($value)
    {
        return '"' . (string)($value) . '"';
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $records = Project::where('approved', true)->get()->map(function ($r) {
                $url = url(self::URL);
                $delete_url = $this->toString($url . '/' . $r->id);
                $actions = '';
                $actions .= "<a href='$url/$r->id' title='Show Record'  ><i data-feather='list' class='fas fa-eye me-2''></i></a>   ";
                $actions .= "<a href='$url/$r->id/edit' title='Edit Record'  ><i data-feather='list' class='fas fa-pen me-2''></i></a>   ";
                $actions .= "<a  href='javascript:'  title='Delete Record' onclick='deleteRecordAjax($delete_url)' class='danger p-0' ><i class='fas fa-trash me-2'></i></a>
                   ";
                return [

                    'name' => $r->name,
                    'location' => $r->location,
                    'logo' => $r->logo,
                    'city' => $r->city->name,
                    'description' => $r->description,
                    'developed_by' => $r->developed_by,
                    'developer_contact' => $r->developer_contact,
                    'actions' => $actions,
                ];
            });
            return DataTables::of($records)
                ->rawColumns(['actions'])
                ->make(true);
        }

        if ($request->wantsJson()) {
            return new ProjectCollection(
                Project::where('approved', true)->whereNotNull(['user_id', 'city_id', 'name', 'location', 'developed_by', 'developer_contact', 'description', 'logo'])->latest()->get()
            );
        }
        return view(self::VIEW . '.index');
    }
    public function modrateProject(Request $request)
    {
        if ($request->ajax()) {

            $records = Project::where('approved', false)->get()->map(function ($r) {
                $url = url(self::URL);

                $check = $r->approved == true ? 'checked'
                    : '';
                $actions = '';
                $actions = "
                        <label class='switch'>
                        <input type='checkbox' onchange='isApproved(event,$r->id)'
                            name='is_featured' value='$r->approved'
                            $check  class='success'>
                        <span class='slider round'></span>
                        </label>
                ";
                return [

                    'name' => $r->name,
                    'location' => $r->location,
                    'logo' => $r->logo,
                    'city' => $r->city->name,
                    'description' => $r->description,
                    'developed_by' => $r->developed_by,
                    'developer_contact' => $r->developer_contact,
                    'actions' => $actions,
                ];
            });
            return DataTables::of($records)
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view(self::VIEW . '.index');
    }

    public function myProject()
    {
        return new ProjectCollection(
            Project::whereNotNull(['user_id', 'city_id', 'name', 'location', 'developed_by', 'developer_contact', 'description', 'logo'])
                ->where('approved', true)->where('user_id', auth()->user()->id)->get()
        );
    }
    public function isFeaturedProject()
    {
        return new ProjectCollection(
            Project::whereNotNull(['user_id', 'city_id', 'name', 'location', 'developed_by', 'developer_contact', 'description', 'logo'])
                ->where('approved', true)->where('is_featured', true)->latest()->get()
        );
    }
    public function create()
    {

        return view(self::VIEW . '.create');
    }


    public function store(Request $request)
    {
      
        $file = $request->file('logo');
        $pdf_file = $request->file('pdf_file');
        $img_project = '';
        $pdf = "";
        $img_name = rand(10, 100) . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/project', $img_name);
        $img_project = $img_name;
        if ($pdf_file) {
            $pdf_name = rand(10, 100) . time() . '.' . $pdf_file->getClientOriginalExtension();
            $request->file('pdf_file')->storeAs('public/project', $pdf_name);
            $pdf = $pdf_name;
        }
        

        $project = Project::create([
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'location' => $request->location,
            'logo' => $img_project,
            'pdf_file' => $pdf,
            'city_id' => $request->city_id,
            'description' => $request->description,
            'is_featured' => $request->is_featured != null ? true : false,
            'featured_phone_number' => $request->featured_phone_number,
            'featured_whatsapp_number' => $request->featured_whatsapp_number,
            'developed_by' => $request->developed_by,
            'approved' => auth()->user()->user_type == 'admin' ? true : false,
            'developer_contact' => $request->developer_contact,
        ]);
        if ($request->images) {

            foreach ($request->images as $image) {
                $name = '';
                $img_name = rand(10, 100) . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/project', $img_name);
                $name = $img_name;
                ProjectImages::create([
                    'image' => $name,
                    'project_id' => $project->id
                ]);
            }
        }
        if ($request->wantsJson()  ) {

            return response()->json(['status' => true, 'message' => self::TITLE . ' Add Successfully'], 200);
        }
        Alert::toast(self::TITLE . ' Add Successfully', 'success');
        return redirect(self::URL);
    }

    public function apiStore(Request $request)
    {
        
        
        $data = [];
        $image1 = $request->file('image1');
        $image2 = $request->file('image2');
        $image3 = $request->file('image3');
        $image4 = $request->file('image4');
        $image5 = $request->file('image5');
        if ($image1 != null) {
            $img1 = array('image1' => $image1);
            array_push($data, $img1);
        }
        if ($image2 != null) {
            $img2 = array('image2' => $image2);
            array_push($data, $img2);
        }
        if ($image3 != null) {
            $img3 = array('image3' => $image3);
            array_push($data, $img3);
        }
        if ($image4 != null) {
            $img4 = array('image4' => $image4);
            array_push($data, $img4);
        }
        if ($image5 != null) {
            $img5 = array('image5' => $image5);
            array_push($data, $img5);
        }

        $file = $request->file('logo');
        $pdf_file = $request->file('pdf_file');
        $img_project = '';
         $pdf_n = '';
         if ($file) {
        $img_name = rand(10, 100) . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/project', $img_name);
        $img_project = $img_name;
         }
        if ($pdf_file) {
            $pdf_name = rand(10, 100) . time() . '.' . $request->file('pdf_file')->getClientOriginalExtension();
           $request->file('pdf_file')->storeAs('public/project', $pdf_name);
            $pdf_n = $pdf_name;
        }

        $project = Project::create([
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'location' => $request->location,
            'logo' => $img_project,
            'pdf_file' => $pdf_n,
            'city_id' => $request->city_id,
            'description' => $request->description,
            'is_featured' => $request->is_featured != null ? true : false,
            'featured_phone_number' => $request->featured_phone_number,
            'featured_whatsapp_number' => $request->featured_whatsapp_number,
            'developed_by' => $request->developed_by ,
            'approved' => auth()->user()->user_type == 'admin' ? true : false,
            'developer_contact' => $request->developer_contact,
        ]);
        if ($data != []) {

            foreach ($data as $images) {
                foreach ($images as $image) {
                    $name = '';
                    $img_name = rand(10, 100) . time() . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('public/project', $img_name);
                    $name = $img_name;
                    ProjectImages::create([
                        'image' => $name,
                        'project_id' => $project->id
                    ]);
                }
            }
        }
        if ($request->wantsJson() || $request->headers('Multi-Part')) {

            return response()->json(['status' => true, 'message' => self::TITLE . ' Add Successfully'], 200);
        }
        Alert::toast(self::TITLE . ' Add Successfully', 'success');
        return redirect(self::URL);
    }
    
    
      public function addProject(Request $request)
    {
         
        $data = [];
        $image1 = $request->file('image1');
        $image2 = $request->file('image2');
        $image3 = $request->file('image3');
        $image4 = $request->file('image4');
        $image5 = $request->file('image5');
        if ($image1 != null) {
            $img1 = array('image1' => $image1);
            array_push($data, $img1);
        }
        if ($image2 != null) {
            $img2 = array('image2' => $image2);
            array_push($data, $img2);
        }
        if ($image3 != null) {
            $img3 = array('image3' => $image3);
            array_push($data, $img3);
        }
        if ($image4 != null) {
            $img4 = array('image4' => $image4);
            array_push($data, $img4);
        }
        if ($image5 != null) {
            $img5 = array('image5' => $image5);
            array_push($data, $img5);
        }

        $file = $request->file('logo');
        $pdf_file = $request->file('pdf_file');
        $img_project = '';
         $pdf_n = '';
         if ($file) {
        $img_name = rand(10, 100) . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/project', $img_name);
        $img_project = $img_name;
         }
        if ($pdf_file) {
            $pdf_name = rand(10, 100) . time() . '.' . $request->file('pdf_file')->getClientOriginalExtension();
           $request->file('pdf_file')->storeAs('public/project', $pdf_name);
            $pdf_n = $pdf_name;
        }
      $user = User::find($request->user_id);
    
        $project = Project::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'location' => $request->location,
            'logo' => $img_project,
            'pdf_file' => $pdf_n,
            'city_id' => $request->city_id,
            'description' => $request->description,
            'is_featured' => $request->is_featured != null ? true : false,
            'featured_phone_number' => $request->featured_phone_number,
            'featured_whatsapp_number' => $request->featured_whatsapp_number,
            'developed_by' => $request->developed_by ,
            'approved' => $user->user_type == 'admin' ? true : false,
            'developer_contact' => $request->developer_contact,
        ]);
        if ($data != []) {

            foreach ($data as $images) {
                foreach ($images as $image) {
                    $name = '';
                    $img_name = rand(10, 100) . time() . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('public/project', $img_name);
                    $name = $img_name;
                    ProjectImages::create([
                        'image' => $name,
                        'project_id' => $project->id
                    ]);
                }
            }
        }
      

            return response()->json(['status' => true, 'message' => self::TITLE . ' Add Successfully'], 200);
    
    }
    
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {

            $records = Product::where('project_id', $id)->get()->map(function ($r) {

                $url = url('products');
                $actions = '';
                $actions .= "<a href='$url/$r->id' title='Show Record'  ><i data-feather='list' class='fas fa-eye me-2''></i></a>   ";

                // $actions .= "<a href='#'title='Show Record' data-bs-toggle='modal' data-bs-target='#exampleModalLarge$r->id' ><i data-feather='list' class='fas fa-eye me-2''></i></a>   ";
                return [
                    'image' => $r->getItemImage(),
                    'title' => $r->title,
                    'size' => $r->size . ' ' . $r->size_name,
                    'product_type' => $r->product_type,
                    'actions' => $actions,
                ];
            });
            return DataTables::of($records)
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view(self::VIEW . '.show', [
            'id' => $id,
            'project' => Project::find($id),
        ]);
    }


    public function edit($id)
    {
        $project = Project::find($id);
        $url_p = url('products');
        $delete_url = $this->toString($url_p . '/' . $id);
        $images = ProjectImages::where('project_id', $id)->get(['id', 'image']);
        $project_url = env('APP_IMAGE_URL') . 'project';
        return view(self::VIEW . '.edit', get_defined_vars());
    }

    public function deleteImage($file)
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }


    public function getImageData($file)
    {
        return ProjectImages::where('project_id', $file)->get();
    }

    public function update(Request $request, $id)
    {
        
        // dd($request->is_featured);
        $project =  Project::find($id);
        try {
            $img_project = '';
            $pdf_n = '';
            $file = $request->logo;
            $pdf_file = $request->file('pdf_file');

            if ($request->logo) {
                $this->deleteImage(url('storage/project') . '/' . $project->logo);
                $img_name = rand(10, 100) . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/project', $img_name);
                $img_project = $img_name;
            }

            if ($request->pdf_file) {
                $this->deleteImage(url('storage/project') . '/' . $project->pdf_file);
                $pdf_name = rand(10, 100) . time() . '.' . $request->pdf_file->getClientOriginalExtension();

                $pdf_file->storeAs('public/project', $pdf_name);
                $pdf_n = $pdf_name;
            }
            $project->update([
                'name' => $request->name ? $request->name : $project->name,
                'location' => $request->location ? $request->name : $project->name,
                'logo' => $img_project ? $img_project : $project->logo,
                'pdf_file' => $pdf_n ? $pdf_n : $project->pdf_file,
                'city_id' => $request->city_id,
                'description' => $request->description,
                'is_featured' => $request->is_featured != null ? true : false,
                'featured_phone_number' => $request->featured_phone_number ? $request->featured_phone_number : $project->featured_phone_number,
                'featured_whatsapp_number' => $request->featured_whatsapp_number ? $request->featured_whatsapp_number : $project->featured_whatsapp_number,
                'developed_by' => $request->developed_by ? $request->developed_by : $project->developed_by,
                'developer_contact' => $request->developer_contact ? $request->developer_contact : $project->developer_contact,
            ]);
            if (!$request->old) {
                ProjectImages::where('project_id', $project->id)->delete();
            } else {
                ProjectImages::where('project_id', $project->id)->whereNotIn('id', $request->old)->delete();
            }
            if ($request->images) {
                foreach ($request->images as $image) {

                    $name = '';
                    $img_name = rand(10, 100) . time() . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('public/project', $img_name);
                    $name = $img_name;
                    ProjectImages::create([
                        'image' => $name,
                        'project_id' => $project->id
                    ]);
                }
            }
            if ($request->wantsJson()) {
                return response()->json(['status' => true, 'message' => self::TITLE . ' Update Successfully'], 200);
            }
            Alert::toast(self::TITLE . 'Update Successfully', 'success');
            return redirect(self::URL);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $e->getMessage()], 200);
            }
            Alert::toast('Error occured' . $e->getMessage(), 'error');
            return view(self::URL . '.edit', get_defined_vars());
        }
    }
    public function apiUpdate(Request $request, $id)
    {
        $data = [];
        $image1 = $request->file('image1');
        $image2 = $request->file('image2');
        $image3 = $request->file('image3');
        $image4 = $request->file('image4');
        $image5 = $request->file('image5');
        if ($image1 != null) {
            $img1 = array('image1' => $image1);
            array_push($data, $img1);
        }
        if ($image2 != null) {
            $img2 = array('image2' => $image2);
            array_push($data, $img2);
        }
        if ($image3 != null) {
            $img3 = array('image3' => $image3);
            array_push($data, $img3);
        }
        if ($image4 != null) {
            $img4 = array('image4' => $image4);
            array_push($data, $img4);
        }
        if ($image5 != null) {
            $img5 = array('image5' => $image5);
            array_push($data, $img5);
        }

        $project =  Project::find($id);
        try {
            $img_project = '';
            $pdf_n = '';
            $file = $request->logo;
            $pdf_file = $request->file('pdf_file');

            if ($request->logo) {
                $this->deleteImage(url('storage/project') . '/' . $project->logo);
                $img_name = rand(10, 100) . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/project', $img_name);
                $img_project = $img_name;
            }

            if ($request->pdf_file) {
                $this->deleteImage(url('storage/project') . '/' . $project->pdf_file);
                $pdf_name = rand(10, 100) . time() . '.' . $request->pdf_file->getClientOriginalExtension();

                $pdf_file->storeAs('public/project', $pdf_name);
                $pdf_n = $pdf_name;
            }
            $project->update([
                'name' => $request->name ? $request->name : $project->name,
                'location' => $request->location ? $request->name : $project->name,
                'logo' => $img_project ? $img_project : $project->logo,
                'pdf_file' => $pdf_n ? $pdf_n : $project->pdf_file,
                'city_id' => $request->city_id,
                'description' => $request->description,
                'is_featured' => $request->is_featured == null ? true : false,
                'featured_phone_number' => $request->featured_phone_number ? $request->featured_phone_number : $project->featured_phone_number,
                'featured_whatsapp_number' => $request->featured_whatsapp_number ? $request->featured_whatsapp_number : $project->featured_whatsapp_number,
                'developed_by' => $request->developed_by ? $request->developed_by : $project->developed_by,
                'developer_contact' => $request->developer_contact ? $request->developer_contact : $project->developer_contact,
            ]);
            if ($data != []) {
                ProjectImages::where('project_id', $project->id)->delete();
                foreach ($data as $images) {
                    foreach ($images as $image) {
                        $name = '';
                        $img_name = rand(10, 100) . time() . '.' . $image->getClientOriginalExtension();
                        $image->storeAs('public/project', $img_name);
                        $name = $img_name;
                        ProjectImages::create([
                            'image' => $name,
                            'project_id' => $project->id
                        ]);
                    }
                }
            }
            if ($request->wantsJson()) {
                return response()->json(['status' => true, 'message' => self::TITLE . ' Update Successfully'], 200);
            }
            Alert::toast(self::TITLE . 'Update Successfully', 'success');
            return redirect(self::URL);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $e->getMessage()], 200);
            }
            Alert::toast('Error occured' . $e->getMessage(), 'error');
            return view(self::URL . '.edit', get_defined_vars());
        }
    }


    public function destroy($id)
    {
        $project = Project::find($id);
        if ($project) {
            ProjectImages::where('project_id', $project->id)->delete();
            $project->delete();
            return 1;
        }
    }
    public function isFeatured(Request $request, $id)
    {
        $product = Project::find($id);
        if ($request->is_featured == null) {
            $product->update([
                'is_featured' => true,
            ]);
            return response()->json(['success' => true, 'message' => 'Featured Change Successfully'], 200);
        } else {
            $product->update([
                'is_featured' => false,
            ]);
            return response()->json(['success' => true, 'message' => 'Featured Change Successfully'], 200);
        }
    }
    public function isApproved(Request $request, $id)
    {
        $product = Project::find($id);
        if ($request->is_approved  == null) {
            $product->update([
                'approved' => true,
            ]);
            return response()->json(['success' => true, 'message' => 'Approved  Successfully'], 200);
        } else {
            $product->update([
                'approved' => false,
            ]);
            return response()->json(['success' => true, 'message' => 'Under Review'], 200);
        }
    }

    public function uploadProjectPdf(Request $request, $id)
    {
        $project = Project::find($id);
        $pdf_n = '';
        $pdf_file = $request->file('pdf_file');
        if ($request->pdf_file  == null) {

            return response()->json(['success' => true, 'message' => 'No file  in a Request'], 200);
        } else {

            $this->deleteImage(url('storage/project') . '/' . $project->pdf_file);
            $pdf_name = rand(10, 100) . time() . '.' . $request->pdf_file->getClientOriginalExtension();

            $pdf_file->storeAs('public/project', $pdf_name);
            $pdf_n = $pdf_name;
            $project->update([
                'pdf_file' => $pdf_n ? env('APP_IMAGE_URL') . 'project' . $pdf_n :  env('APP_IMAGE_URL') . 'project' . $project->pdf_file,
            ]);
            return response()->json(['success' => true, 'message' => 'File Uploaded Successfully', 'data' => $pdf_n ? '' . $pdf_n : $project->pdf_file], 200);
        }
    }
}
