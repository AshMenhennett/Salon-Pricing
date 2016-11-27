<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductUpdateFormRequest;
use App\Http\Requests\ProductCreateFormRequest;

class ProductsController extends Controller
{
    public function fetchProducts (Request $request)
    {
        //return $request->user()->products;
        return Product::where('user_id', $request->user()->id)->orderBy('brand', 'asc')->get();
    }

    public function index ()
    {
        return view('products.index');
    }

    public function create ()
    {
        return view('products.create.index');
    }

    public function submit (ProductCreateFormRequest $request)
    {
        // authorize

        $user = $request->user();
        $product = new Product();
        $product->user_id = $user->id;
        $product->name = $request->name;
        $product->brand = $request->brand;
        $product->price = $request->price;

        $product->save();

        return redirect()->route('products.index');

    }

    public function edit (Request $request, Product $product)
    {
        return view('products.product.edit.index', [
            'product' => $product,
        ]);
    }

    public function update (ProductUpdateFormRequest $request, Product $product)
    {
        // authorize

        $product->name = $request->name;
        $product->brand = $request->brand;
        $product->price = $request->price;
        $product->save();

        return redirect()->route('products.index');

    }

    public function destroy (Request $request, Product $product)
    {
        // authorize
        //
        $product->delete();

        return response()->json(null, 200);
    }
}
