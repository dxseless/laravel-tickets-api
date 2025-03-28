<?php

namespace App\Traits;

trait ApiResponces
{
    public function success($message, $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }

    public function ok($message)
    {
        return $this->success($message, 200);
    }
}
