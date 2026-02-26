<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskAssigned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;
    public $assigneeIds;

    /**
     * Create a new event instance.
     */
    public function __construct(Task $task, array $assigneeIds)
    {
        $this->task = $task;
        $this->assigneeIds = $assigneeIds;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        $channels = [];
        foreach ($this->assigneeIds as $assigneeId) {
            $channels[] = new \Illuminate\Broadcasting\PrivateChannel('employee.' . $assigneeId);
        }
        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'task.assigned';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'project_id' => $this->task->project_id,
            'message' => 'You have been assigned a new task: ' . $this->task->title,
        ];
    }
}
