<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function include(string $relationship)
    {
        $includedValue = request()->get('include');

        if (! isset($includedValue)) {
            return false;
        }

        $includedValues = explode(',', strtolower($includedValue));

        return in_array(strtolower($relationship), $includedValues);
    }
}
