<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class AbstractApiController extends Controller
{
    public function createResponse($data = [], $status = 200, $message = 'Success.'): JsonResponse
    {
        return response()->json(
            data: [
                'data' => $data,
                'status' => $status,
                'message' => $message,
                'success' => $status === 200,
            ],
            status: $status);
    }
}
