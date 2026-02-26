<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'name'                    => $this->name,
            'description'             => $this->description,
            'status'                  => $this->status,
            'priority'                => $this->priority,
            'start_date'              => $this->start_date?->format('Y-m-d'),
            'end_date'                => $this->end_date?->format('Y-m-d'),
            'creator_name'            => $this->whenLoaded('creator', fn () => $this->creator?->name ?? 'Admin', 'Admin'),
            'members'                 => $this->whenLoaded('members', function () {
                return $this->members->map(fn ($member) => [
                    'id'   => $member->id,
                    'name' => $member->user?->name ?? 'N/A',
                ]);
            }),
            'members_count'           => $this->whenLoaded('members', fn () => $this->members->count(), 0),
            'tasks_count'             => $this->whenLoaded('tasks', fn () => $this->tasks->count(), 0),
            'completed_tasks_count'   => $this->whenLoaded('tasks', fn () => $this->tasks->where('status', 'completed')->count(), 0),
            'tasks'                   => $this->whenLoaded('tasks', function () {
                return $this->tasks->map(fn ($task) => [
                    'id'     => $task->id,
                    'title'  => $task->title,
                    'status' => $task->status,
                ]);
            }),
            'created_at'              => $this->created_at?->toISOString(),
            'updated_at'              => $this->updated_at?->toISOString(),
            'deleted_at'              => $this->deleted_at?->toISOString(),
        ];
    }
}
