<?php

namespace KhantNyar\ServiceExtender\Services\Contracts;

use Illuminate\Database\Eloquent\Model;

interface EloquenceInterface
{
    public static function query();

    public static function first();
    
    public static function all();

    public static function find(string $fieldOrValue,mixed $value = null);

    public static function create(array $data);

    public static function update(Model $id, array $data);

    public static function delete(Model $id);
}
