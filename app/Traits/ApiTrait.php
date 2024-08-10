<?php

namespace App\Traits;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;


trait ApiTrait
{

    public function successResponse($data = [], $message = null, $locale = null)
    {
        $locale = $locale ?? App::getLocale();
        $response = config('responses.success')[$locale];

        return response()->json([
            'status' => 200,
            'message' => $message ?? $response,
            'data' => $data,
        ]);
    }

    public function errorResponse($message = null, $status = null, $locale = null)
    {
        $locale = $locale ?? App::getLocale();
        $response = config('responses.error')[$locale];

        return response()->json([
            'status' => 404,
            'message' => $message ?? $response,
            'data' => [],
        ]);
    }

    public function validationResponse($errors = [], $message = null, $locale = null)
    {
        $locale = $locale ?? App::getLocale();
        $response = config('responses.validation')[$locale];

        return response()->json([
            'status' => 404,
            'message' => $message ?? $response,
            'data' => $errors,
        ]);
    }

    public function notFoundResponse($message = null, $locale = null)
    {
        $locale = $locale ?? App::getLocale();
        $response = config('responses.not_found')[$locale];

        return response()->json([
            'status' => 404,
            'message' => $message ?? $response,
            'data' => [],
        ]);
    }
}