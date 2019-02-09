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
    protected function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * response the http request
     *
     * @param mixed $data
     * @param array $header
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respond($data, $header = [])
    {
        return response()->json(
            wrap_with_data($data), $this->getStatusCode(), $header
        );
    }

    /**
     * fill the error message and status code into array
     *
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithError($message): \Illuminate\Http\JsonResponse
    {
        $error = [
            'message' => $message,
            'status_code' => $this->getStatusCode(),
        ];

        return $this->respond(compact('error'));
    }
}
