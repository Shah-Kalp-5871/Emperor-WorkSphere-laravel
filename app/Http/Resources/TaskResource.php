<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'project_name' => $this->whenLoaded('project', fn () => $this->project->name),
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date?->format('Y-m-d'),
            'assignees' => $this->whenLoaded('assignees', function () {
                return $this->assignees->map(function ($assignee) {
                    return [
                        'id' => $assignee->id,
                        'name' => $assignee->first_name . ' ' . $assignee->last_name,
                    ];
                });
            }),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
