<?php

namespace App\Http\Helpers;

class ApiResponse
{
    public static function success($data = [], $message = "Operación exitosa.", $status = 200, $meta = [])
    {
        $meta['api_version'] = config('api.version');

        return response()->json([
            'success' => true,
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
        ], $status);
    }

    public static function error($message = "Algo salió mal.", $status = 400, $errors = [])
    {
        return response()->json([
            'success' => false,
            'status' => $status,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}