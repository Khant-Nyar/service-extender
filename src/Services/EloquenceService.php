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
    //,HasDatatable
    // use HasDatatable;
    /**
     * The model class to be used in the service.
     *
     * @var class-string<Model>
     */
    protected static string $model;
    
    protected static string $primaryKey;


    public static function make(array $options = []): Builder
    {
        return static::$model::query();
    }

    public static function query(): Builder
    {
        return static::$model::query();
    }

    public static function get()
    {
        return static::$model::get();
    }

    public static function first()
    {
        return static::$model::first();
    }

    public static function all()
    {
        return static::$model::all();
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


    // public static function find(mixed $value)
    // {
    //     $model = static::$model;

    //     // Define the default searchable fields
    //     $searchableFields = ['id', 'uuid', 'slug'];

    //     // Filter only fillable fields that exist in the model
    //     $fillableFields = array_intersect($searchableFields, (new $model)->getFillable());

    //     // Build the query to search in any of the defined fields
    //     return $model::where(function ($query) use ($fillableFields, $value) {
    //         foreach ($fillableFields as $field) {
    //             $query->orWhere($field, $value);
    //         }
    //     })->first();
    // }

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
