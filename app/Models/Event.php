<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'featured_image',
        'starts_at',
        'ends_at',
        'location',
        'speaker_name',
        'timezone',
        'all_day',
        'recurrence_frequency',
        'recurrence_on',
        'exclusion_dates',
    ];

    protected $appends = [
        'featured_image_url',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'all_day' => 'boolean',
            'recurrence_on' => 'date',
            'exclusion_dates' => 'array',
        ];
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image
            ? Storage::disk('public')->url($this->featured_image)
            : null;
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = 'event';
        }

        $slug = $base;
        $i = 1;
        while (static::query()
            ->when($ignoreId !== null, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    /** Payload for admin preview and public event page (Inertia). */
    public function toPreviewPayload(): array
    {
        $tz = $this->timezone ?: 'UTC';

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'description' => $this->description ?? '',
            'speaker_name' => $this->speaker_name,
            'location' => $this->location,
            'timezone' => $tz,
            'date_summary' => $this->formatDateSummary(),
            'recurrence_summary' => $this->formatRecurrenceSummary(),
            'exclusions_summary' => $this->formatExclusionsSummary(),
            'featured_image_url' => $this->featured_image_url,
        ];
    }

    public function formatDateSummary(): string
    {
        $tz = $this->timezone ?: 'UTC';
        $starts = Carbon::parse($this->starts_at)->timezone($tz);
        $ends = $this->ends_at ? Carbon::parse($this->ends_at)->timezone($tz) : null;

        if ($this->all_day) {
            $startStr = $starts->format('l, F j, Y');
            if ($ends && ! $starts->isSameDay($ends)) {
                $endStr = $ends->format('l, F j, Y');

                return "All day · {$startStr} – {$endStr}";
            }

            return "All day · {$startStr}";
        }

        $fmt = 'M j, Y g:i A';
        $startStr = $starts->format($fmt);
        if ($ends) {
            $sameDay = $starts->isSameDay($ends);
            $endStr = $sameDay ? $ends->format('g:i A') : $ends->format($fmt);

            return "{$startStr} – {$endStr}";
        }

        return $startStr;
    }

    public function formatRecurrenceSummary(): string
    {
        $freq = $this->recurrence_frequency ?? 'none';
        if ($freq === 'none') {
            return 'Does not repeat.';
        }
        if ($freq === 'once') {
            $on = $this->recurrence_on
                ? $this->recurrence_on->format('F j, Y')
                : '—';

            return "One multi-day anchor · {$on}";
        }

        return 'Repeats '.ucfirst($freq).'.';
    }

    public function formatExclusionsSummary(): string
    {
        $dates = $this->exclusion_dates ?? [];
        if (count($dates) === 0) {
            return '';
        }

        $parts = array_map(
            fn ($d) => Carbon::parse($d)->format('M j, Y'),
            $dates
        );

        return implode(', ', $parts);
    }
}
