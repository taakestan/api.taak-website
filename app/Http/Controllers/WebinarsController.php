<?php

namespace App\Http\Controllers;


use App\Http\Resources\WebinarResource;
use App\Models\Provider;
use App\Models\Webinar;
use App\Tools\Base64Generator;

class WebinarsController extends Controller {

    use Base64Generator;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return WebinarResource::collection(
            Webinar::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $this->validate($request, [
            'title' => 'required|string',
            'label' => 'required|string',
            'description' => 'required|string', // this not optimized db structure, in the future we must separate this into own table
            'content' => 'required|string', // this not optimized db structure, in the future we must separate this into own table
            'provider_id' => 'required|exists:providers,id'
        ]);

        $webinar = Provider::findOrFail($validated['provider_id'])
            ->webinars()
            ->create($validated);

        return $this->respondCreated(
            'وبینار جدید ایجاد شد', new WebinarResource($webinar)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Webinar $webinar
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(\Illuminate\Http\Request $request, Webinar $webinar)
    {
        $validated = $this->validate($request, [
            'title' => 'required|string',
            'label' => 'required|string',
            'description' => 'required|string', // this not optimized db structure, in the future we must separate this into own table
            'content' => 'required|string', // this not optimized db structure, in the future we must separate this into own table
            'provider_id' => 'required|exists:providers,id'
        ]);

        $webinar->forceFill($validated)->save();

        return $this->respondCreated(
            'وبینار جدید ایجاد شد', new WebinarResource($webinar)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Webinar $webinar
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Webinar $webinar)
    {
        $webinar->delete();

        return $this->respondDeleted();
    }
}
