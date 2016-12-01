<?php

namespace App\Http\Controllers;

use DB;
use Fractal;
use App\Service;
use Illuminate\Http\Request;
use App\Http\Requests\ServiceUpdateFormRequest;
use App\Http\Requests\ServiceCreateFormRequest;

class ServicesController extends Controller
{

    public function fetchServices (Request $request)
    {
        return Fractal::collection($this->fetchServiceCategories($request))->transformWith(
            function ($category) {
                return [
                    // building custom data structure, including categories of services and the services them selves. Utlized in ServicesTableComponent.vue.
                    'cat_name' => $category->category,
                    'services' => Service::where('category', $category->category)->where('user_id', \Auth::user()->id)->orderBy('title', 'desc')->get()
                ];
            }
        )->toArray();
    }

    public function fetchServiceCategories (Request $request) {
        // get all distinct categories, that belong to the user's service and where the value is not an empty string
        return DB::table('services')->select('category')->where('category', '!=', '')->where('user_id', $request->user()->id)->orderBy('category', 'asc')->distinct()->get();
    }

    public function index ()
    {
        return view('services.index');
    }

    public function create ()
    {
        return view('services.create.index');
    }

    public function submit (ServiceCreateFormRequest $request)
    {
        $user = $request->user();

        $service = new Service();
        $service->user_id = $user->id;
        $service->title = $request->title;
        $service->price = $request->price;
        $service->category = $request->category;
        $service->save();

        return redirect()->route('services.index');
    }

    public function edit (Request $request, Service $service)
    {
        if ($request->user()->can('edit', $service)) {
            return view('services.service.edit.index', [
                'service' => $service,
            ]);
        }

        return redirect()->route('home');
    }

    public function update (ServiceUpdateFormRequest $request, Service $service)
    {
        if ($request->user()->can('update', $service)) {
            $service->title = $request->title;
            $service->price = $request->price;
            $service->category = $request->category;
            $service->save();
        }

        return redirect()->route('services.index');
    }

    public function destroy (Request $request, Service $service)
    {
        if ($request->user()->can('destroy', $service)) {
            $service->delete();
            return response()->json(null, 200);
        }

        return response()->json(null, 403);
    }
}
