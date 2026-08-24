<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AuditLogObserver
{
    /**
     * Columns excluded from diffs to reduce noise (they change on every write).
     *
     * @var list<string>
     */
    private const EXCLUDED_KEYS = ['created_at', 'updated_at', 'deleted_at'];

    public function created(Model $model): void
    {
        $attributes = Arr::except($model->getAttributes(), self::EXCLUDED_KEYS);

        // Strip null values so the payload is compact on create
        $attributes = array_filter($attributes, fn ($value): bool => $value !== null);

        $this->record($model, 'created', [], $attributes);
    }

    public function updated(Model $model): void
    {
        $changes = Arr::except($model->getChanges(), self::EXCLUDED_KEYS);

        if (empty($changes)) {
            return;
        }

        $original = Arr::only($model->getOriginal(), array_keys($changes));

        $this->record($model, 'updated', $original, $changes);
    }

    public function deleted(Model $model): void
    {
        $attributes = Arr::except($model->getAttributes(), self::EXCLUDED_KEYS);

        $this->record($model, 'deleted', $attributes, []);
    }

    public function forceDeleted(Model $model): void
    {
        $attributes = Arr::except($model->getAttributes(), self::EXCLUDED_KEYS);

        $this->record($model, 'force_deleted', $attributes, []);
    }

    /**
     * @param  array<string, mixed>  $old
     * @param  array<string, mixed>  $new
     */
    private function record(Model $model, string $action, array $old, array $new): void
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $model->getMorphClass(),
            'subject_id' => $model->getKey(),
            'payload' => [
                'old' => $old,
                'new' => $new,
            ],
        ]);
    }
}
