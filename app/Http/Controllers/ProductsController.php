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

    /**
     * Returns an array, like: [{"data": [{brand_name: string, categories: [{category: string, products: [{prod1}, {...}, {...}]}, ..., ...]}, ..., ...]}]
     * Utilized by ProductsTableComponent, ProductPricingComponent and AllPricingComponent Vue components.
     *
     * @param  Illuminate\Http\Request $request
     * @return array
     */
    public function fetchProducts (Request $request)
    {
        return Fractal::collection($this->fetchProductsWithDistinctBrand($request))->transformWith(
            function ($product) {
                // technically $categories is a collection of products, with a category property, but it works resonably well..
                $categories = $this->fetchProductsWithDistinctCategoryForBrand($product->brand);
                foreach ($categories as $category) {
                    // adding dynamic property to each $category
                    // $category->category is the actual category field from db.
                    $category->products = $this->fetchProductsForCategoryAndBrand($category->category, $product->brand);
                }
                return [
                    // building data structure, including brands of products, associated categories and the products themselves.
                    'brand_name' => $product->brand,
                    'categories' => $categories
                ];
            }
        )->toArray();
    }

    /**
     * Returns all products that have a distinct brand associated with them. The returned products only have a brand property.
     * Utilized by fetchProducts($request) and ProductBrandSelectComponent Vue component.
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Support\Collection
     */
    public function fetchProductsWithDistinctBrand (Request $request) {
        return DB::table('products')->select('brand')->where('brand', '!=', '')->where('user_id', $request->user()->id)->orderBy('brand', 'asc')->distinct()->get();
    }

    /**
     * Returns all products that have a distinct category associated with them. The returned products only have a category property.
     * Utilized by ProductCategorySelectComponent Vue component.
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Support\Collection
     */
    public function fetchProductsWithDistinctCategory (Request $request) {
        return DB::table('products')->select('category')->where('category', '!=', '')->where('user_id', $request->user()->id)->orderBy('category', 'asc')->distinct()->get();
    }

    /**
     * Returns all products that belong to a specific brand. The returned products only have a category property.
     * Utilized by fetchProducts($request)
     *
     * @param  string $brand
     * @return Illuminate\Support\Collection
     */
    protected function fetchProductsWithDistinctCategoryForBrand (string $brand) {
        return DB::table('products')->select('category')->where('category', '!=', '')->where('brand', $brand)->where('user_id', Auth::user()->id)->orderBy('category', 'asc')->distinct()->get();
    }

    /**
     * Returns all products that belong to both a category and brand.
     * Utilized by fetchProducts($request)
     *
     * @param  string $category
     * @param  string $brand
     * @return Illuminate\Database\Eloquent\Collection
     */
    protected function fetchProductsForCategoryAndBrand (string $category, string $brand) {
        return Product::where('category', $category)->where('brand', $brand)->where('user_id', Auth::user()->id)->orderby('name', 'asc')->get();
    }

    /**
     * Displays product list (ProductsTableComponent Vue component).
     *
     * @return Illuminate\Http\Repsonse
     */
    public function index ()
    {
        return view('products.index');
    }

    /**
     * Displays product creation form.
     * ProductBrandSelectComponent and ProductCategorySelectComponent Vue component is used in the returned view.
     *
     * @return Illuminate\Http\Repsonse
     */
    public function create ()
    {
        return view('products.create.index');
    }

    /**
     * Handles product creation form submission.
     *
     * @param App\Http\Requests\ProductCreateFormRequest $request
     * @return Illuminate\Http\Repsonse
     */
    public function submit (ProductCreateFormRequest $request)
    {
        $request->user()->products()->create([
            'brand' => $request->brand,
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
        ]);

        return redirect()->route('products.index');
    }

    /**
     * Displays populated form to edit a Product.
     * ProductBrandSelectComponent and ProductCategorySelectComponent Vue component is used in the returned view.
     *
     * @param  Illuminate\Http\Request $request
     * @param  App\Product $product
     * @return Illuminate\Http\Response
     */
    public function edit (Request $request, Product $product)
    {
        $this->authorize('edit', $product);

        return view('products.product.edit.index', [
            'product' => $product,
        ]);
    }

    /**
     * Updates a Product.
     *
     * @param  App\Http\RequestsProductUpdateFormRequest $request
     * @param  App\Product                               $product
     * @return Illuminate\Http\Response
     */
    public function update (ProductUpdateFormRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $product->update([
            'brand' => $request->brand,
            'name'  => $request->name,
            'price' => $request->price,
            'category' => $request->category,
        ]);

        return redirect()->route('products.index');
    }

    /**
     * Deletes a Product.
     * Utilized by ProductsTableComponent Vue component.
     *
     * @param  Illuminate\Http\Request $request
     * @param  App\Product             $product
     * @return Illuminate\Http\Response
     */
    public function destroy (Request $request, Product $product)
    {
        $this->authorize('destroy', $product);

        $product->delete();

        return response()->json(null, 200);
    }
}
