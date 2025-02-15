<?php

namespace KhantNyar\ServiceExtender\Services;

use Illuminate\Support\Facades\Http;
use KhantNyar\ServiceExtender\Services\Contracts\ThirpartyApiInterface;

abstract class ApiService implements ThirpartyApiInterface
{
    protected static string $base;
    protected static string $api_key;

    public function build(){
        /** Build Api CLinet with $base url */
    }

    public function make(){
        // return Http::
    }

    public function get(){
        $this->make();
    }

    public function post (){
        $this->make();
    }

}
