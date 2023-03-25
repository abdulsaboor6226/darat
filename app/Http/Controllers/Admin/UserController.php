<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Auth;
use Alert;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
    const TITLE = 'Users';
    const VIEW = 'user';
    const URL = 'users';
    // const IMAGE_SRC = 'images/members/';

    public function __construct()
    {

        view()->share([
            'url' => url(self::URL),
            'title' => self::TITLE,
        ]);
    }
    public function toString($value)
    {
        return '"' . (string)($value) . '"';
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $records = User::where('user_type', '!=', 'admin')->get()->map(function ($r) {
                $url = url(self::URL);
                $delete_url = $this->toString($url . '/' . $r->id);
                $actions = '';
                // $actions .= "<a href='$url/$r->id' title='Show Record'  ><i data-feather='list' class='fas fa-eye me-2''></i></a>   ";
                // $actions .= "<a href='$url/$r->id/edit' title='Edit Record'  ><i data-feather='list' class='fas fa-pen me-2''></i></a>   ";
                // $actions .= "<a  href='javascript:'  title='Delete Record' onclick='deleteRecordAjax($delete_url)' class='danger p-0' ><i class='fas fa-trash me-2'></i></a>
                //    ";
                return [

                    'id' => $r->id,
                    'name' => $r->name,
                    'email' => $r->email,
                    'phone' => $r->phone,
                    'facebook_connects' => '',
                    'no_of_projects' => count($r->projects),
                    'device' => $r->device,
                    'actions' => $actions,
                ];
            });
            return DataTables::of($records)
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view(self::VIEW . '.index');
    }


    public function create()
    {
        return view(self::VIEW . '.create');
    }


    public function store(Request $request)
    {
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
    }


    public function update(Request $request)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        $file = $request->image;
        $img_user = '';
        if ($file) {
            $img_name = rand(10, 100) . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/user', $img_name);
            $img_user = $img_name;
        }
        if ($request->construction_company  || $request->real_state_agent || $request->architect) {
            $user_type = 'business';
        }else if ($user->user_type =='admin'){
            $user_type = 'admin';
        }else {
            $user_type = 'personal';
        }
        $user->update([
            'name' => $request->name ? $request->name : $user->name,
            'user_type' => $user_type,
            'ceo_name' => $request->ceo_name ? $request->ceo_name : $user->ceo_name,
            'phone' => $request->phone ? $request->phone : $user->phone,
            'email' => $request->email ? $request->email : $user->email,
            'image' => $img_user ? $img_user : $user->image,
            'company_name' => $request->company_name ? $request->company_name : $user->company_name,
            'whatsapp_number' => $request->whatsapp_number ? $request->whatsapp_number : $user->whatsapp_number,
            'mobile_number_1' => $request->mobile_number_1 ? $request->mobile_number_1 : $user->mobile_number_1,
            'mobile_number_2' => $request->mobile_number_2 ? $request->mobile_number_2 : $user->mobile_number_2,
            'company_address' => $request->company_address ? $request->company_address : $user->company_address,
            'about_us' => $request->about_us ? $request->about_us : $user->about_us,
            'construction_company' => $request->construction_company,
            'real_state_agent' => $request->real_state_agent,
            'architect' => $request->architect,
        ]);
        return response()->json(['status' => true, 'message' => 'Profile Update Successfully', 'date' => $user], 200);
    }


    public function destroy($id)
    {
    }
}
