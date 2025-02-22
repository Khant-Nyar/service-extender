<?php

trait getDatatable{
    public  static function getDatatable(){
        
        return DataTables::of(static::$model::make())
            ->addColumn('phone', fn ($each) => $each->profile->phone ?? '---')
            ->addColumn('department', fn ($each) => $each->department->name)
            ->addColumn('roles', function ($each) use ($roles) {
                $userRoles = $each->roles->pluck('id')->toArray();

                return view('components.form.select2', [
                    'name'        => "roles[{$each->id}]",
                    'options'     => $roles,
                    'selected'    => $userRoles,
                    'placeholder' => 'Select roles',
                    'multiple'    => true,
                    'id'          => "roles-{$each->id}",
                    'class'       => 'form-select select2',
                    'data_id'     => $each->id,
                ])->render();
            })
            ->addColumn('role_status', fn ($each) => view('components.form.switch', [
                'name'    => "role_status[{$each->id}]",
                'checked' => $each->profile?->is_random_role ?? false,
                'id'      => $each->id,
                'url'     => route('users.update', $each->id),
            ])->render())
            ->addColumn('action', fn ($each) => view('components.datatable.action-buttons', [
                'data'        => $each,
                'editRoute'   => 'users.edit',
                'deleteRoute' => 'users.destroy'])
                ->render())
            ->filterColumn('role_name', function ($query, $keyword): void {
                $query->whereHas('role', function ($q) use ($keyword): void {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['roles', 'role_status', 'role_name', 'action'])
            ->toJson();
    }
}