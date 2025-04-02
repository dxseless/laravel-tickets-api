<?php

namespace App\Traits;

trait ApiResponces
{
    public function success($message, $data, $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $statusCode,
        ], $statusCode);
    }

    public function error($message, $statusCode)
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }

    public function ok($message, $data = [])
    {
        return $this->success($message, $data, 200);
    }
}
