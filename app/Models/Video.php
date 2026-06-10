<?php
// === FILE: app/Models/Video.php ===

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $lesson_id
 * @property string|null $r2_key
 * @property string|null $url
 * @property int $duration_seconds
 * @property int|null $size_bytes
 * @property string|null $mime_type
 * @property string $status processing|ready|error
 * @property Lesson $lesson
 */
class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'r2_key',
        'url',
        'duration_seconds',
        'size_bytes',
        'mime_type',
        'status',
    ];

    protected $casts = [
    ];

    // ===== RELATIONSHIPS =====

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    // ===== SCOPES =====

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    public function scopeError($query)
    {
        return $query->where('status', 'error');
    }

    // ===== HELPERS =====

    public function isReady(): bool
    {
        return $this->status === 'ready';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isError(): bool
    {
        return $this->status === 'error';
    }

    public function getDurationFormatted()
    {
        $seconds = $this->duration_seconds;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
        }
        return sprintf('%02d:%02d', $minutes, $secs);
    }
}
