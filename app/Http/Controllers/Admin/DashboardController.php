<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Package;
use App\Models\Project;
use App\Models\User;
use App\Models\RateList;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    const URL = 'dashboard';

    // const IMAGE_SRC = 'images/members/';

    public function __construct()
    {

        view()->share([
            'url' => url(self::URL),
            'project_url' => env('APP_IMAGE_URL') . 'project',
        ]);
    }
    public function toString($value)
    {
        return '"' . (string)($value) . '"';
    }

    public function index(Request $request)
    {
   
        if ($request->ajax()) {

            $records = Project::where('approved', false)->get()->map(function ($r) {
                $url = url(self::URL);
                $check = $r->approved == true ? 'checked'
                    : '';
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
        $projects = Project::count();
        $rate_lists = RateList::count();
        $packages = Package::count();
        return view('dashboard.index', get_defined_vars());
    }
    public function cities()
    {
        $cities = City::all();
        return response()->json(['status' => true, 'data' => get_defined_vars()], 200);
    }
   public function accountDeactivate(Request $request)
    {
        $user = User::find($request->user_id);
        $user->update([
            'status' => 0,
        ]);
        return response()->json(['status' => true, 'message' => 'Account Deactivate Successfully'], 200);
    }
}
