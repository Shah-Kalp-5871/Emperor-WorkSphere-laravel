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
            'id'            => $this->id,
            'project_id'    => $this->project_id,
            'project_name'  => $this->whenLoaded('project', fn () => $this->project->name),
            'title'         => $this->title,
            'description'   => $this->description,
            'status'        => $this->status,
            'priority'      => $this->priority,
            'due_date'      => $this->due_date?->format('Y-m-d'),
            'creator_name'  => $this->whenLoaded('creator', fn () => $this->creator?->name ?? 'Admin', 'Admin'),
            'assignees'     => $this->whenLoaded('assignees', function () {
                return $this->assignees->map(fn ($assignee) => [
                    'id'   => $assignee->id,
                    'name' => $assignee->user?->name ?? 'N/A',
                ]);
            }),
            'created_at'    => $this->created_at?->toISOString(),
            'updated_at'    => $this->updated_at?->toISOString(),
            'deleted_at'    => $this->deleted_at?->toISOString(),
        ];
    }
}
