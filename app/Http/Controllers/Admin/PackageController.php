<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Auth;
use App\Models\Package;
use Carbon\Carbon;
use Alert;
use App\Http\Resources\PackageCollection;

class PackageController extends Controller
{
    const TITLE = 'Package';
    const VIEW = 'package';
    const URL = 'packages';
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

            $records = Package::all()->map(function ($r) {
                $url = url(self::URL);
                $delete_url = $this->toString($url . '/' . $r->id);
                $actions = '';
                $actions .= "<a href='$url/$r->id/edit' title='Edit Record'  ><i data-feather='list' class='fas fa-pen me-2''></i></a>   ";
                $actions .= "<a  href='javascript:'  title='Delete Record' onclick='deleteRecordAjax($delete_url)' class='danger p-0' ><i class='fas fa-trash me-2'></i></a>
                   ";
                return [
                    'title' => $r->title,
                    'price' => $r->price,
                    'desc' => $r->desc,
                    'actions' => $actions,
                ];
            });
            return DataTables::of($records)
                ->rawColumns(['actions'])
                ->make(true);
        }
        if ($request->wantsJson()) {
            return new PackageCollection(
                Package::latest()->get(),
            );
        }
        return view(self::VIEW . '.index');
    }


    public function create()
    {
        return view(self::VIEW . '.create');
    }


    public function store(Request $request)
    {
        $package = Package::create([
            'title' => $request->title,
            'price' => $request->price,
            'desc' => $request->description,
        ]);
        Alert::toast(self::TITLE . ' Add Successfully', 'success');
        return redirect(self::URL);
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        return view(self::VIEW . '.edit', [
            'package' => Package::find($id),
        ]);
    }


    public function update(Request $request, $id)
    {
        $package = Package::find($id);
        $package->update([
            'title' => $request->title,
            'price' => $request->price,
            'desc' => $request->description,
        ]);
        Alert::toast(self::TITLE . ' Update Successfully', 'success');
        return redirect(self::URL);
    }


    public function destroy($id)
    {
        $package = Package::find($id);
        if ($package) {
            $package->delete();
            return 1;
        }
    }
}
