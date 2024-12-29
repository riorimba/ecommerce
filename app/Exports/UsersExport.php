<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select('users.id', 'users.name', 'users.email', 'roles.role_name as role', 'users.created_at', 'users.updated_at')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'email',
            'role',
            'created_at',
            'updated_at',
        ];
    }
}
