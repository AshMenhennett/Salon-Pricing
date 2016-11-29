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
        if ($request->user()->can('edit', $product)) {
            return view('products.product.edit.index', [
                'product' => $product,
            ]);
        }

        return redirect()->route('home');
    }

    public function update (ProductUpdateFormRequest $request, Product $product)
    {
        if ($request->user()->can('update', $product)) {
            $product->name = $request->name;
            $product->brand = $request->brand;
            $product->price = $request->price;
            $product->save();
        }

        return redirect()->route('products.index');
    }

    public function destroy (Request $request, Product $product)
    {
        if ($request->user()->can('destroy', $product)) {
            $product->delete();
            return response()->json(null, 200);
        }

        return response()->json(null, 403);
    }
}
