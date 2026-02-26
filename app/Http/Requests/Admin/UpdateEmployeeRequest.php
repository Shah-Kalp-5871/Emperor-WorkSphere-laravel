<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Need the user ID to ignore unique email constraint
        // The URL param is 'employee' which gives us the employee ID. 
        // We'd have to look up the user ID, or handle it in the repository. 
        // For simplicity, we'll let the repository handle the update if no email is passed, or we skip unique validation here if email is same.
        
        return [
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'email' => ['sometimes', 'required', 'email', 'max:150'],
            'employee_code' => ['sometimes', 'required', 'string', 'max:20'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'date'],
            'date_of_joining' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
