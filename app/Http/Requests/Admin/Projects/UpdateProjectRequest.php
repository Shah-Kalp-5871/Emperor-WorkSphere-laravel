<?php

namespace App\Http\Requests\Admin\Projects;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['sometimes', 'required', 'string', 'max:150'],
            'description'  => ['nullable', 'string'],
            'start_date'   => ['nullable', 'date'],
            'end_date'     => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'       => ['sometimes', 'in:planning,active,completed,on_hold'],
            'priority'     => ['sometimes', 'in:low,medium,high'],
            'employee_ids' => ['nullable', 'array'],
            'employee_ids.*' => ['integer', 'exists:employees,id'],
        ];
    }
}
