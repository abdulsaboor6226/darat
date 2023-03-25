<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Auth;
use Alert;
use App\Http\Resources\RateListCollection;
use App\Models\City;
use App\Models\Project;
use App\Models\RateList;
use App\Models\RateListDetail;
use Carbon\Carbon;

class RateListController extends Controller
{
    const TITLE = 'Rate Lists';
    const VIEW = 'rate_list';
    const URL = 'rate-lists';
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

            $records = RateList::all()->map(function ($r) {
                $url = url(self::URL);
                $delete_url = $this->toString($url . '/' . $r->id);
                $actions = '';
                $actions .= "<a href='$url/$r->id' title='Show Record'  ><i data-feather='list' class='fas fa-eye me-2''></i></a>   ";
                $actions .= "<a href='$url/$r->id/edit' title='Edit Record'  ><i data-feather='list' class='fas fa-pen me-2''></i></a>   ";
                $actions .= "<a  href='javascript:'  title='Delete Record' onclick='deleteRecordAjax($delete_url)' class='danger p-0' ><i class='fas fa-trash me-2'></i></a>
                   ";
                return [
                    'project' => $r->project->name,
                    'division' => $r->division,
                    'city' => $r->city->name,
                    'desc' => $r->desc,
                    'actions' => $actions,
                ];
            });
            return DataTables::of($records)
                ->rawColumns(['actions'])
                ->make(true);
        }
        if ($request->wantsJson()) {
           return new RateListCollection(
               RateList::paginate(10),
           );
        }
        return view(self::VIEW . '.index');
    }


    public function create()
    {

        return view(self::VIEW . '.create', [
            'projects' => Project::all(),
            'cities' => City::all(),
        ]);
    }

    public function saveMeta($request, $id)
    {
        foreach ($request['rate_list'] as $detail) {
            $rate_list_detail = RateListDetail::create([
                'rate_list_id' => $id,
                'name' => $detail['name'],
                'size' => $detail['size'],
                'size_name' => $detail['size_name'],
                'min_price' => $detail['min_price'],
                'max_price' => $detail['max_price'],
            ]);
        }
    }
    public function store(Request $request)
    {
        $rate_list = RateList::create([
            'project_id' => $request->project_id,
            'city_id' => $request->city_id,
            'division' => $request->division,
            'desc' => $request->description,
        ]);
        $this->saveMeta($request, $rate_list->id);
        Alert::toast(self::TITLE . ' Add Successfully', 'success');
        return redirect(self::URL);
    }


    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $records = RateListDetail::where('rate_list_id', $id)->get()->map(function ($r) {
                return [
                    'division' => $r->name,
                    'size' => $r->size . ' ' . $r->size_name,
                    'min_price' => $r->min_price,
                    'max_price' => $r->max_price,
                ];
            });
            return DataTables::of($records)
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view(self::VIEW . '.show', [
            'id' => $id,
            'rate_list' => RateList::find($id),
        ]);
    }


    public function edit($id)
    {
        return view(self::VIEW . '.edit', [
            'rate_list' => RateList::with('rateListDetails')->find($id),
            'projects' => Project::all(),
            'cities' => City::all(),

        ]);
    }


    public function update(Request $request, $id)
    {
        $rate_list = RateList::find($id);
        $rate_list->update([
            'project_id' => $request->project_id,
            'city_id' => $request->city_id,
            'division' => $request->division,
            'desc' => $request->description,
        ]);
        RateListDetail::where('rate_list_id', $id)->delete();
        $this->saveMeta($request, $rate_list->id);
        Alert::toast(self::TITLE . ' Update Successfully', 'success');
        return redirect(self::URL);
    }


    public function destroy($id)
    {
        $rate_list = RateList::find($id);
        if ($rate_list) {
            $rate_list_detail = RateListDetail::where('rate_list_id', $id)->get();
            if ($rate_list_detail) {
                RateListDetail::where('rate_list_id', $id)->delete();
            }
            $rate_list->delete();
            return 1;
        }
    }
}
