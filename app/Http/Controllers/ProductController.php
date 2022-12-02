<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = ProductResource::collection(Product::all());
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'label' => 'required|string',
                'price' => 'required',
                'quantity' => 'required',
                'user_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response($validator->errors(), 406);
        }
        $validate = $validator->validated();

        if ($request->hasFile('photo')) {
            $path = $request->File('photo')->store('images', 'public');
            $validate['photo'] = 'storage/' . $path;
        }
        $product = Product::create($validate);
        if (isset($product)) {
            return response($product, 201);
        } else {
            return response(['message' => "error in product creation"], 417);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (isset($product)) {
            return response(new ProductResource($product));
        } else {
            return response(['message' => "Product with id $id not found", 404]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $product = Product::find($id);
        $data = $request->all();
        if ($request->hasFile('photo')) {
            $path = $request->File('photo')->store('images', 'public');
            $data['photo'] = 'storage/' . $path;
        }

        if ($product->update($data)) {
            return response($product, 201);
        } else {
            return response(['message' => "error in updating product info"], 417);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product->delete()) {
            return response($product);
        } else {
            return response(['message' => "Product with id $id not deleted"]);
        }

    }

}