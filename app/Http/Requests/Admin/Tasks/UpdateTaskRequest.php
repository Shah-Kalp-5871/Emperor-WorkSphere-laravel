<?php

namespace App\Http\Requests\Admin\Tasks;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['sometimes', 'required', 'exists:projects,id'],
            'title' => ['sometimes', 'required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'required', 'in:pending,in_progress,completed,on_hold'],
            'priority' => ['sometimes', 'required', 'in:low,medium,high,urgent'],
            'due_date' => ['nullable', 'date'],
        ];
    }
}
