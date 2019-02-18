<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class ApiController extends BaseController {

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * the http request status code
     *
     * @var int
     */
    protected $statusCode = 200;


    /**
     * get the http status code
     *
     * @return int
     */
    protected function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * set the http status code
     *
     * @param int $statusCode
     * @return ApiController
     */
    protected function setStatusCode(int $statusCode): ApiController
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * response the http request
     *
     * @param $resource
     * @param null $message
     * @param array $header
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respond($message = null, $resource = null, $header = [])
    {
        $data = [];
        if ($resource) $data = array_merge(['data' => $resource]);
        if ($message) $data = array_merge($data, ['message' => $message]);

        return response()->json($data, $this->getStatusCode(), $header);
    }

    /**
     * response the http created 201
     *
     * @param $resource
     * @param null $message
     * @param array $header
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated($message = null, $resource = null, $header = [])
    {
        return $this->setStatusCode(201)
            ->respond($message, $resource, $header);
    }

    /**
     * response the http created 201
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondDeleted()
    {
        return $this->setStatusCode(204)
            ->respond();
    }

    /**
     * fill the error message and status code into array
     *
     * @param array $errors
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithErrors($message = 'مشکلی بوجود آمده است!', $errors = []): \Illuminate\Http\JsonResponse
    {
        return $this->respond($message, compact('errors'));
    }
}
