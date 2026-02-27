<?php

namespace App\Http\Requests\Employee\Projects;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:150'],
            'description'  => ['nullable', 'string'],
            'start_date'   => ['nullable', 'date'],
            'end_date'     => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'       => ['nullable', 'in:planning,active,on_hold,completed'],
            'priority'     => ['nullable', 'in:low,medium,high,critical'],
            'employee_ids' => ['nullable', 'array'],
            'employee_ids.*' => ['integer', 'exists:employees,id'],
        ];
    }
}
