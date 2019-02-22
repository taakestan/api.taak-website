<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProvidersController extends Controller {

    /**
     * ProvidersController constructor.
     */
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
        return ProviderResource::collection(
            Provider::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string',
            'biography' => 'required|string',
            'profiles' => 'nullable|array'
        ]);

        $provider = Provider::create($validated);

        return $this->respondCreated(
            'یک ارایه دهنده جدید ایجاد شد', new ProviderResource($provider)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return ProviderResource
     */
    public function show($id)
    {
        return new ProviderResource(
            Provider::findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request,$id)
    {
        $validated = $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string',
            'biography' => 'required|string',
            'profiles' => 'nullable|array'
        ]);

        Provider::findOrFail($id)->update($validated);

        return $this->respond('بروزرسانی انجام شد');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $provider = Provider::findOrFail($id);

            if ($provider->hasWebinar()) {
                \Illuminate\Support\Facades\Log::info('An Admin want to delete a provider! but some webinar assign that provider :)');
                return $this->respondWithErrors('امکان حذف به دلیل اختصاص یافتن وبینار به این ارایه دهنده وجود ندارد!');
            }

            $provider->delete();
            return $this->respondDeleted();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error in Delete provider:" . $e->getMessage());

            return $this->respondWithErrors('خطایی رخ داده است٬ گزارش خطا ثبت شد');
        }
    }
}
