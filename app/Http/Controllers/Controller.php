<?php

namespace App\Http\Controllers;

class Controller extends ApiController {
    /**
     * handle the http 404 status code
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message = 'Not found!'): \Illuminate\Http\JsonResponse
    {
        return $this->setStatusCode(404)
            ->respondWithErrors($message);
    }

    /**
     * handle the http 500 status code
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondInternalError($message = 'Internal Error!'): \Illuminate\Http\JsonResponse
    {
        return $this->setStatusCode(500)
            ->respondWithErrors($message);
    }
}
