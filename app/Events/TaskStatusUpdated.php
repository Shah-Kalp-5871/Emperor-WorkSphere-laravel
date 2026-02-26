<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Task $task;
    public string $oldStatus;
    public string $newStatus;

    public function __construct(Task $task, string $oldStatus, string $newStatus)
    {
        $this->task      = $task;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Broadcast on each assignee's private channel.
     */
    public function broadcastOn(): array
    {
        $channels = [];

        // Load assignees if not already loaded
        $assignees = $this->task->assignees ?? $this->task->load('assignees')->assignees;

        foreach ($assignees as $assignee) {
            $channels[] = new PrivateChannel('employee.' . $assignee->id);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'task.status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'task_id'     => $this->task->id,
            'title'       => $this->task->title,
            'project_id'  => $this->task->project_id,
            'old_status'  => $this->oldStatus,
            'new_status'  => $this->newStatus,
            'message'     => "Task \"{$this->task->title}\" status changed from {$this->oldStatus} to {$this->newStatus}.",
        ];
    }
}
