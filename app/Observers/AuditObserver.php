<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function created(Model $model): void
    {
        $this->logActivity('created', $model);
    }

    public function updated(Model $model): void
    {
        if (empty($model->getChanges())) {
            return;
        }
        $this->logActivity('updated', $model, $model->getOriginal(), $model->getChanges());
    }

    public function deleted(Model $model): void
    {
        $this->logActivity('deleted', $model);
    }

    protected function logActivity(string $event, Model $model, array $oldValues = null, array $newValues = null): void
    {
        try {
            // Try admin guard first, fall back to api (employee) guard
            $user = auth('admin')->user() ?? auth('api')->user();

            AuditLog::create([
                'user_type'      => $user ? get_class($user) : null,
                'user_id'        => $user?->id,
                'event'          => $event,          // ✅ correct column name
                'auditable_type' => get_class($model), // ✅ correct morph column
                'auditable_id'   => $model->id,       // ✅ correct morph column
                'old_values'     => $oldValues,
                'new_values'     => $newValues,
                'ip_address'     => request()->ip(),
                'user_agent'     => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to write audit log: ' . $e->getMessage());
        }
    }
}
