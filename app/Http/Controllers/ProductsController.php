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
        return Fractal::collection($this->fetchProductBrands($request))->transformWith(
            function ($brand) {
                $categories = $this->fetchProductCategoriesForBrand($brand);
                foreach ($categories as $category) {
                    // adding dynamic props to each $category
                    // cat_name prop is sugar syntax for category prop of $category, for readability in vue component
                    $category->cat_name = $category->category;
                    $category->products = $this->fetchProductsForCategoryAndBrand($category, $brand);
                }
                return [
                    'brand_name' => $brand->brand,
                    'categories' => $categories
                ];
            }
        )->toArray();
    }

    public function fetchProductBrands (Request $request) {
        // get all distinct brands, that belong to the user's products and where the value is not an empty string
        return DB::table('products')->select('brand')->where('brand', '!=', '')->where('user_id', $request->user()->id)->orderBy('brand', 'asc')->distinct()->get();
    }

    public function fetchProductCategories (Request $request) {
        // get all distinct categories, that belong to the user's products, where the value is not an empty string
        return DB::table('products')->select('category')->where('category', '!=', '')->where('user_id', $request->user()->id)->orderBy('category', 'asc')->distinct()->get();
    }

    protected function fetchProductCategoriesForBrand ($brand) {
        // get all distinct categories, that belong to the user's products, where the value is not an empty string and that are associated with a particular brand
        return DB::table('products')->select('category')->where('category', '!=', '')->where('brand', $brand->brand)->where('user_id', Auth::user()->id)->orderBy('category', 'asc')->distinct()->get();
    }

    protected function fetchProductsForCategoryAndBrand ($category, $brand) {
        // get all distinct categories, that belong to the user's products, where the value is not an empty string and that are associated with a particular brand
        return Product::where('category', $category->category)->where('brand', $brand->brand)->where('user_id', Auth::user()->id)->orderby('name', 'asc')->get();
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
