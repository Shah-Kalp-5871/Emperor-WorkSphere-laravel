<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        $this->logActivity('created', $model);
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Model $model): void
    {
        // Don't log if only timestamps changed
        if (empty($model->getChanges())) {
            return;
        }

        $this->logActivity('updated', $model, $model->getOriginal(), $model->getChanges());
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $this->logActivity('deleted', $model);
    }

    /**
     * Process and insert the audit log.
     */
    protected function logActivity(string $action, Model $model, array $oldValues = null, array $newValues = null): void
    {
        try {
            $user = auth('api')->user();
            
            AuditLog::create([
                'user_id' => $user ? $user->id : null,
                'action' => $action,
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to write audit log: ' . $e->getMessage());
        }
    }
}
