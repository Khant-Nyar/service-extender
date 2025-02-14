<?php

namespace App\Services\App\Interface;

interface EloquenceInterface
{
    public static function all();
    public static function find(int $id);
    public static function create(array $data);
    public static function update(int $id, array $data);
    public static function delete(int $id);
}
