<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection, WithHeadingRow, WithValidation, WithBatchInserts, WithUpserts
{
    use Importable;

    public function collection(Collection  $rows)
    {
        $departments = Department::pluck('id', 'name');

        $data = $rows->map(function($row) use ($departments) {
            return [
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'password' => Hash::make($row['password']),
                'department_id' => $departments[$row['department']] ?? null,
                'status' => $row['status'],
            ];
        })->filter(fn($row) => $row['department_id'])->toArray();

        User::upsert($data, ['email'], [
            'name',
            'email',
            'phone',
            'password',
            'department_id',
            'status',
        ]);

        $emails = array_column($data, 'email');
        $users = User::whereIn('email', $emails)->get();

        foreach ($users as $user) {
            if ($user && $user->roles->isNotEmpty()) {
                $user->assignRole('worker');
            }
        }
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function uniqueBy()
    {
        return 'codigo';
    }

    public function rules(): array
    {
        // Define las reglas de validación para las cabeceras y los valores
        return [
            '*.name' => 'required',
            '*.email' => 'required',
            '*.phone' => 'required',
            '*.password' => 'required',
            '*.department' => 'required|exists:departments,name',
            '*.status' => 'required|in:active,inactive',
        ];
    }

    public function customValidationMessages()
    {
        // Mensajes de error personalizados
        return [
            '*.name.required' => "You don't have a name",
            '*.email.required' => "You don't have an email",
            '*.password.required' => "You don't have a password",
            '*.phone.required' => "You don't have a phone number",
            '*.department.required' => "You don't have a department",
            '*.department.exists' => "You have an unknown department",
            '*.status.required' => "You don't have a status",
            '*.status.in' => "You have an unknown status",
        ];
    }
}
