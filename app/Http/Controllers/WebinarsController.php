<?php

namespace App\Http\Controllers;


use App\Http\Resources\WebinarResource;
use App\Models\Webinar;
use App\Tools\Base64Generator;

class WebinarsController extends Controller {

    use Base64Generator;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'description' => 'required|string',
            'content' => 'required|string'
        ]);

        $webinar = Webinar::create($validated);

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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(\Illuminate\Http\Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
