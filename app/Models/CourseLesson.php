<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CourseLesson extends Model
{
    public const CONTENT_VIDEO = 'video';

    public const CONTENT_IMAGE = 'image';

    public const CONTENT_PDF = 'pdf';

    public const CONTENT_TEXT = 'text';

    protected $fillable = [
        'course_section_id',
        'title',
        'sort_order',
        'duration_minutes',
        'content_type',
        'video_url',
        'image_path',
        'pdf_path',
        'body',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
        ];
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path
            ? Storage::disk('public')->url($this->image_path)
            : null;
    }

    public function getPdfUrlAttribute(): ?string
    {
        return $this->pdf_path
            ? Storage::disk('public')->url($this->pdf_path)
            : null;
    }
}
