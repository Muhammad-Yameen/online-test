<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\File;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data'=>Product::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->all();
        if($request->hasFile('image')){
            $imageName = time().'.'.$request->image->extension();
            // Public Folder
            $request->image->move(public_path('images'), $imageName);
            $data['image'] = $imageName;
        }
        $product = Product::create($data);
        return response()->json(['message' => 'Product Store Successfully','data'=>$product]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->all();
        if($request->file('image')){
            // File::delete(public_path('images').'/'.$product->image);
            $imageName = time().'.'.$request->image->extension();
            // Public Folder
            $request->image->move(public_path('images'), $imageName);
            $data['image'] = $imageName;
        }

        $response  =  $product->update($data);
        return response()->json(['message' => 'Product Update Successfully','data'=>$response]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $response =  $product->delete();
        return response()->json(['message' => 'Product Deleted Successfully','data'=>$response]);
    }
}
