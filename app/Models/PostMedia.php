<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostMedia extends BaseModel
{
    use HasUuid;

    protected $fillable = [
        'post_id',
        'type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'order',
        'metadata',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'order' => 'integer',
        'metadata' => 'array',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
