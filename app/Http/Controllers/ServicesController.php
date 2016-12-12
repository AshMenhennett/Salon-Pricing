<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Fractal;
use App\Service;
use Illuminate\Http\Request;
use App\Http\Requests\ServiceUpdateFormRequest;
use App\Http\Requests\ServiceCreateFormRequest;


class ServicesController extends Controller
{

    /**
     * Returns an array like: [{"data": [{category: string, services: [{service1}, {...}, {...}]}, ..., ....]}]
     * Utlized by ServicesTableComponent, ServicePricingComponent and AllPricingComponent Vue components.
     *
     * @param  Illuminate\Http\Request $request
     * @return array
     */
    public function fetchServices (Request $request)
    {
        return Fractal::collection($this->fetchProductsWithDistinctCategory($request))->transformWith(
            function ($product) {
                return [
                    // building data structure, including categories of services and the services themselves.
                    'category' => $product->category,
                    'services' => Service::where('category', $product->category)->where('user_id', Auth::user()->id)->orderBy('title', 'asc')->get()
                ];
            }
        )->toArray();
    }

    /**
     * Returns all services that have a distinct category associated with them. Returned services will only have a category property.
     * Utilized by fetchServices($request) and ServiceCategorySelectComponent Vue component.
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Support\Collection
     */
    public function fetchProductsWithDistinctCategory (Request $request) {
        return DB::table('services')->select('category')->where('category', '!=', '')->where('user_id', $request->user()->id)->orderBy('category', 'asc')->distinct()->get();
    }

    /**
     * Displays services list (ServicesTableComponent Vue component).
     *
     * @return Illuminate\Http\Response
     */
    public function index ()
    {
        return view('services.index');
    }

    /**
     * Displays service creation form.
     * ServiceCategorySelectComponent Vue component is used in the returned view.
     *
     * @return Illuminate\Http\Response
     */
    public function create ()
    {
        return view('services.create.index');
    }

    /**
     * Handles service creation form submission.
     *
     * @param  App\Http\Requests\ServiceCreateFormRequest $request
     * @return Illuminate\Http\Response
     */
    public function submit (ServiceCreateFormRequest $request)
    {
        $request->user()->services()->create([
            'title' => $request->title,
            'price' => $request->price,
            'category' => $request->category,
        ]);

        return redirect()->route('services.index');
    }

    /**
     * Displays populated form to edit a Service.
     * ServiceCategorySelectComponent Vue component is used in the returned view.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  App\Service              $service
     * @return Illuminate\Http\Response
     */
    public function edit (Request $request, Service $service)
    {
        $this->authorize('edit', $service);

        return view('services.service.edit.index', [
            'service' => $service,
        ]);
    }

    /**
     * Updates a Service.
     *
     * @param  App\Http\Requests\ServiceUpdateFormRequest   $request
     * @param  App\Service                                  $service
     * @return Illuminate\Http\Response
     */
    public function update (ServiceUpdateFormRequest $request, Service $service)
    {
        $this->authorize('update', $service);

        $service->update([
            'title' => $request->title,
            'price' => $request->price,
            'category' => $request->category,
        ]);

        return redirect()->route('services.index');
    }

    /**
     * Deletes a Service.
     * Utilized by ServicesTableComponent Vue component.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  App\Service              $service
     * @return Illuminate\Http\Response
     */
    public function destroy (Request $request, Service $service)
    {
        $this->authorize('destroy', $service);

        $service->delete();

        return response()->json(null, 200);
    }
}
