<?php

namespace KhantNyar\ServiceExtender\Services;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use KhantNyar\ServiceExtender\Services\Contracts\EloquenceInterface;

abstract class EloquenceService implements EloquenceInterface
{
    /**
     * The model class to be used in the service.
     *
     * @var class-string<Model>
     */
    protected static string $model;

    public static function query(): Builder
    {
        return static::$model::query();
    }

    public static function get()
    {
        return static::$model::get();
    }

    public static function all()
    {
        return static::$model::all();
    }

    public static function find(int $id)
    {
        return static::$model::find($id);
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

    public static function update(int $id, array $data)
    {
        DB::beginTransaction();

        try {
            $model = static::$model::find($id);

            if (! $model) {
                throw new Exception(static::$model." with ID {$id} not found.");
            }

            $model->update($data);
            Log::info('Record updated in '.static::$model, ['id' => $id]);

            DB::commit();

            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating record in '.static::$model, ['error' => $e->getMessage()]);

            throw new Exception('Failed to update record: '.$e->getMessage());
        }
    }

    public static function delete(int $id)
    {
        DB::beginTransaction();

        try {
            $model = static::$model::find($id);

            if (! $model) {
                throw new Exception(static::$model." with ID {$id} not found.");
            }

            $model->delete();
            Log::info('Record deleted in '.static::$model, ['id' => $id]);

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting record in '.static::$model, ['error' => $e->getMessage()]);

            throw new Exception('Failed to delete record: '.$e->getMessage());
        }
    }
}
