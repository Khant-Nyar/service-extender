<?php

namespace KhantNyar\ServiceExtender\Services;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use KhantNyar\ServiceExtender\Services\Contracts\EloquenceInterface;

abstract class EloquenceService implements EloquenceInterface
{
    protected static string $model;
    protected static string $primaryKey;

    // Magic methods for full Eloquent access
    public static function __callStatic($method, $parameters)
    {
        $query = static::$model::query();
        if (method_exists($query, $method)) {
            return $query->$method(...$parameters);
        }
        if (method_exists(static::$model, $method)) {
            return static::$model::$method(...$parameters);
        }
        throw new Exception("Method {$method} does not exist on model or query builder");
    }

    public function __call($method, $parameters)
    {
        $query = static::$model::query();
        if (method_exists($query, $method)) {
            return $query->$method(...$parameters);
        }
        throw new Exception("Method {$method} does not exist on query builder");
    }

    public static function find(string $fieldOrValue, mixed $value = null)
    {
        $model = static::$model;
        $defaultFields = ['id', 'uuid', 'slug'];

        if ($value === null) {
            $searchableFields = array_intersect($defaultFields, (new $model)->getFillable());
            return $model::where(function ($query) use ($searchableFields, $fieldOrValue) {
                foreach ($searchableFields as $field) {
                    $query->orWhere($field, $fieldOrValue);
                }
            })->first();
        }

        if (in_array($fieldOrValue, (new $model)->getFillable())) {
            return $model::where($fieldOrValue, $value)->first();
        }

        throw new Exception("Invalid search field: {$fieldOrValue}");
    }

    public static function create(array $data)
    {
        DB::beginTransaction();
        try {
            $record = static::$model::create($data);
            Log::info('Record created in '.static::$model, ['id' => $record->id]);
            DB::commit();
            return $record;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating record in '.static::$model, ['error' => $e->getMessage()]);
            throw new Exception('Failed to create record: '.$e->getMessage());
        }
    }

    public static function update(Model $model, array $data)
    {
        DB::beginTransaction();
        try {
            $model->update($data);
            Log::info('Record updated in '.static::$model, ['id' => $model->id]);
            DB::commit();
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating record in '.static::$model, ['error' => $e->getMessage()]);
            throw new Exception('Failed to update record: '.$e->getMessage());
        }
    }

    public static function delete(Model $model)
    {
        DB::beginTransaction();
        try {
            $model->delete();
            Log::info('Record deleted in '.static::$model, ['id' => $model->id]);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting record in '.static::$model, ['error' => $e->getMessage()]);
            throw new Exception('Failed to delete record: '.$e->getMessage());
        }
    }
}