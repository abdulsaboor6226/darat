<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Auth;
use Alert;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProjectCollection;
use App\Models\InstallmentImages;
use App\Models\Product;
use App\Models\ProductInstallment;
use App\Models\Project;
use Carbon\Carbon;

class ProductController extends Controller
{
    const TITLE = 'Product';
    const VIEW = 'product';
    const URL = 'products';

    // const IMAGE_SRC = 'images/members/';

    public function __construct()
    {

        view()->share([
            'url' => url(self::URL),
            'title' => self::TITLE,
            'project_url' => env('APP_IMAGE_URL') . 'project',
            'product_url' => env('APP_IMAGE_URL') . 'product',
        ]);
    }

    public function toString($value)
    {
        return '"' . (string)($value) . '"';
    }



    public function create()
    {
        $projects = Project::all();
        return view(self::VIEW . '.create', get_defined_vars());
    }


    public function store(ProductRequest $request)
    {
        // $request->validate([
        //     'images'=>'required',
        // ]);
        $product = Product::create([
            'title' => $request->title,
            'size' => $request->size,
            'size_name' => $request->size_name,
            'user_id' => Auth::id(),
            'product_type' => $request->product_type,
            'project_id' => $request->project_id
        ]);
        $this->saveInstallment($request, $product->id);

        if ($request->wantsJson()) {
            return response()->json(['status' => true, 'message' => self::TITLE . ' Add Successfully'], 200);
        }
        Alert::toast(self::TITLE . ' Add Successfully', 'success');
        return redirect('/projects');
    }
    public function saveInstallment($request, $id)
    {
        foreach ($request->plot as $type) {
            ProductInstallment::create([
                'title' => $type['title'],
                'amount' => $type['amount'],
                'time' => $type['time'],
                'product_id' => $id
            ]);
        }
        if ($request->images) {
            foreach ($request->images as $image) {

                $name = '';
                $img_name = rand(10, 100) . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/product', $img_name);
                $name = $img_name;
                InstallmentImages::create([
                    'image' => $name,
                    'product_id' => $id
                ]);
            }
        }
    }

    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $records = ProductInstallment::where('product_id', $id)->get()->map(function ($r) {

                return [
                    'title' => $r->title,
                    'amount' => $r->amount,
                    'time' => $r->time,
                ];
            });
            return DataTables::of($records)
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view(self::VIEW . '.show', [
            'id' => $id,
            'product' => Product::find($id),
        ]);
    }


    public function edit($id)
    {
        $product = Product::find($id);
        $projects = Project::all();

        $images = InstallmentImages::where('product_id', $id)->get(['id', 'image']);
        $product_url = env('APP_IMAGE_URL') . 'product';

        return view(self::VIEW . '.edit', get_defined_vars());
    }

    public function deleteImage($file)
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function update(Request $request, $id)
    {

        $product =  Product::find($id);
        try {

            // $project
            $product->update([
                'title' => $request->title ? $request->title : $product->title,
                'size' => $request->size ? $request->size : $product->size,
                'size_name' => $request->size_name ? $request->size_name : $product->size_name,
                'product_type' => $request->product_type ? $request->product_type : $product->product_type,
                'project_id' => $request->project_id ? $request->project_id : $product->project_id
            ]);
            ProductInstallment::where('product_id', $product->id)->delete();
            if (!$request->old) {
                InstallmentImages::where('product_id', $product->id)->delete();
            } else {
                InstallmentImages::where('product_id', $product->id)->whereNotIn('id', $request->old)->delete();
            }


            $this->saveInstallment($request, $product->id);
            if ($request->wantsJson()) {
                return response()->json(['status' => true, 'message' => self::TITLE . ' Update Successfully'], 200);
            }
            Alert::toast(self::TITLE . 'Update Successfully', 'success');
            return redirect('/projects');
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
        $product = Product::find($id);
        if ($product) {
            $product_intallments = ProductInstallment::where('product_id', $product->id)->delete();
            $intallment_images = InstallmentImages::where('product_id', $product->id)->delete();
            $product->delete();
            return 1;
        }
    }
}
