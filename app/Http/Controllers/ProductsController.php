<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Fractal;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductUpdateFormRequest;
use App\Http\Requests\ProductCreateFormRequest;

class ProductsController extends Controller
{
    public function fetchProducts (Request $request)
    {
        return Fractal::collection($this->fetchProductsWithDistinctBrand($request))->transformWith(
            function ($product) {
                $categories = $this->fetchProductsWithDistinctCategoryGivenBrand($product->brand);
                foreach ($categories as $category) {
                    // adding dynamic props to each $category
                    $category->products = $this->fetchProductsForCategoryAndBrand($category->category, $product->brand);
                }
                return [
                    // building data structure, including brands of products, associated categories and the products them selves. Utlized in ProductsTableComponent.vue.
                    'brand_name' => $product->brand,
                    'categories' => $categories
                ];
            }
        )->toArray();
    }

    public function fetchProductsWithDistinctBrand (Request $request) {
        // get all products with a distinct brand, that belong to the user
        return DB::table('products')->select('brand')->where('brand', '!=', '')->where('user_id', $request->user()->id)->orderBy('brand', 'asc')->distinct()->get();
    }

    public function fetchProductsWithDistinctCategory(Request $request) {
        // get all products with a distinct category, that belong to the user
        return DB::table('products')->select('category')->where('category', '!=', '')->where('user_id', $request->user()->id)->orderBy('category', 'asc')->distinct()->get();
    }

    protected function fetchProductsWithDistinctCategoryGivenBrand ($brand) {
        // get all products with a distinct category, given a brand
        return DB::table('products')->select('category')->where('category', '!=', '')->where('brand', $brand)->where('user_id', Auth::user()->id)->orderBy('category', 'asc')->distinct()->get();
    }

    protected function fetchProductsForCategoryAndBrand ($category, $brand) {
        // get all products associated with a specific category and brane
        return Product::where('category', $category)->where('brand', $brand)->where('user_id', Auth::user()->id)->orderby('name', 'asc')->get();
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
        $product->brand = $request->brand;
        $product->name = $request->name;
        $product->category = $request->category;
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
            $product->brand = $request->brand;
            $product->name = $request->name;
            $product->price = $request->price;
            $product->category = $request->category;
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
