<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected $builder;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function filter($array)
    {
        foreach ($this->$array as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        return $builder;
    }

    protected function getRequest()
    {
        return $this->request;
    }
}
