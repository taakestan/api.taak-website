<?php

namespace App\Http\Controllers;


use App\Http\Resources\WebinarResource;
use App\Models\Provider;
use App\Models\Webinar;
use App\Tools\Base64Generator;

class WebinarsController extends Controller {

    use Base64Generator;

    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return WebinarResource::collection(
            Webinar::paginate(6)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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
            'provider_id' => 'required|exists:providers,id',
            'links' => 'nullable|array',
            'image' => 'required', //TODO: this is should move to morph table of images future
            'banner' => 'required'  //TODO: this is should move to morph table of images future
        ]);


        try {
            $image = $this->createFileFromBase64($validated['image']);
            $banner = $this->createFileFromBase64($validated['banner']);
        } catch (\App\Exceptions\InvalidBase64Data $e) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                "image or banner" => 'base64 invalid data'
            ]);
        }

        \Illuminate\Support\Facades\Validator::make(compact('image', 'banner'), [
            'image' => 'required|file|mimes:jpeg,jpg,png',
            'banner' => 'required|file|mimes:jpeg,jpg,png'
        ])->validate();

        $validated['image'] = \Illuminate\Support\Facades\Storage::disk('media')->putFile('webinars', $image);
        $validated['banner'] = \Illuminate\Support\Facades\Storage::disk('media')->putFile('webinars', $banner);


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
     * @param int $id
     * @return WebinarResource
     */
    public function show($id)
    {
        return new WebinarResource(
            Webinar::findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(\Illuminate\Http\Request $request, $id)
    {
        $validated = $this->validate($request, [
            'title' => 'required|string',
            'label' => 'required|string',
            'description' => 'required|string', // this not optimized db structure, in the future we must separate this into own table
            'content' => 'required|string', // this not optimized db structure, in the future we must separate this into own table
            'provider_id' => 'required|exists:providers,id',
            'links' => 'nullable|array',
            'image' => 'required',
            'banner' => 'required',
        ]);

        $webinar = Webinar::findOrFail($id);
        try {
            $image = $this->createFileFromBase64($validated['image']);

            \Illuminate\Support\Facades\Validator::make(compact('image'), [
                'image' => 'required|file|mimes:jpeg,jpg,png'
            ])->validate();

            $validated['image'] = \Illuminate\Support\Facades\Storage::disk('media')
                ->putFile('webinars', $image);

        } catch (\App\Exceptions\InvalidBase64Data $e) {
        }

        try {
            $banner = $this->createFileFromBase64($validated['banner']);

            \Illuminate\Support\Facades\Validator::make(compact('banner'), [
                'banner' => 'required|file|mimes:jpeg,jpg,png'
            ])->validate();

            $validated['banner'] = \Illuminate\Support\Facades\Storage::disk('media')
                ->putFile('webinars', $banner);

            $webinar->forceFill($validated);
        } catch (\App\Exceptions\InvalidBase64Data $e) {
        }

        $webinar->save();

        return $this->respond('بروزرسانی انجام شد');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $webinar = Webinar::findOrFail($id);
            \Illuminate\Support\Facades\Storage::disk('media')->delete(
                joinPath('webinars', $webinar->image)
            );
            \Illuminate\Support\Facades\Storage::disk('media')->delete(
                joinPath('webinars', $webinar->banner)
            );

            $webinar->delete();
            return $this->respondDeleted();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error in Delete Webinar:" . $e->getMessage());

            return $this->respondWithErrors('خطایی رخ داده است٬ گزارش خطا ثبت شد');
        }
    }
}
