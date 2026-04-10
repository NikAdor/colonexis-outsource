<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    private const RECURRENCE = ['none', 'daily', 'weekly', 'monthly', 'yearly', 'once'];

    public function index(): Response
    {
        $events = Event::query()
            ->orderByDesc('starts_at')
            ->paginate(15);

        return Inertia::render('Admin/Events/Index', [
            'events' => $events,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Events/Create', [
            'defaultTimezone' => config('app.timezone'),
            'timezones' => timezone_identifiers_list(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeEventRequest($request);
        $base = $this->validateBase($request);
        $dates = $this->parseEventDates($request);
        $data = array_merge($base, $dates);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('events', 'public');
        }

        $data['slug'] = Event::generateUniqueSlug($request->title);

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event created.');
    }

    public function preview(Event $event): Response
    {
        return Inertia::render('Admin/Events/Preview', [
            'event' => $event->toPreviewPayload(),
        ]);
    }

    public function edit(Event $event): Response
    {
        return Inertia::render('Admin/Events/Edit', [
            'event' => $this->prepareEventForForm($event),
            'defaultTimezone' => config('app.timezone'),
            'timezones' => timezone_identifiers_list(),
        ]);
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->normalizeEventRequest($request);
        $base = $this->validateBase($request);
        $dates = $this->parseEventDates($request);
        $data = array_merge($base, $dates);

        if ($request->hasFile('featured_image')) {
            if ($event->featured_image) {
                Storage::disk('public')->delete($event->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('events', 'public');
        } elseif ($request->boolean('remove_featured_image') && $event->featured_image) {
            Storage::disk('public')->delete($event->featured_image);
            $data['featured_image'] = null;
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event updated.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        if ($event->featured_image) {
            Storage::disk('public')->delete($event->featured_image);
        }
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted.');
    }

    private function normalizeEventRequest(Request $request): void
    {
        $request->merge([
            'recurrence_on' => $request->filled('recurrence_on') ? $request->recurrence_on : null,
        ]);
    }

    private function validateBase(Request $request): array
    {
        $tzList = timezone_identifiers_list();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'speaker_name' => 'nullable|string|max:255',
            'timezone' => ['required', 'string', Rule::in($tzList)],
            'all_day' => 'boolean',
            'recurrence_frequency' => ['required', 'string', Rule::in(self::RECURRENCE)],
            'recurrence_on' => 'nullable|date',
            'exclusion_dates' => 'nullable|array',
            'exclusion_dates.*' => 'date',
            'featured_image' => 'nullable|image|max:5120',
            'remove_featured_image' => 'boolean',
        ]);

        $validated['all_day'] = $request->boolean('all_day');
        $validated['remove_featured_image'] = $request->boolean('remove_featured_image');

        $exclusions = array_values(array_filter(
            $validated['exclusion_dates'] ?? [],
            fn ($d) => $d !== null && $d !== ''
        ));
        $validated['exclusion_dates'] = count($exclusions) ? $exclusions : null;

        unset($validated['featured_image'], $validated['remove_featured_image']);

        return $validated;
    }

    private function parseEventDates(Request $request): array
    {
        $tz = $request->input('timezone', 'UTC');
        if (! in_array($tz, timezone_identifiers_list(), true)) {
            $tz = 'UTC';
        }

        if ($request->boolean('all_day')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            $start = Carbon::parse($request->input('start_date'), $tz)->startOfDay();
            $endInput = $request->input('end_date') ?: $request->input('start_date');
            $end = Carbon::parse($endInput, $tz)->endOfDay();

            return [
                'starts_at' => $start->utc(),
                'ends_at' => $end->utc(),
            ];
        }

        $request->validate([
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date',
        ]);

        $starts = Carbon::parse($request->input('starts_at'), $tz);
        $ends = $request->filled('ends_at')
            ? Carbon::parse($request->input('ends_at'), $tz)
            : null;

        if ($ends && $ends->lt($starts)) {
            throw ValidationException::withMessages([
                'ends_at' => 'The end time must be after or equal to the start time.',
            ]);
        }

        return [
            'starts_at' => $starts->utc(),
            'ends_at' => $ends?->utc(),
        ];
    }

    private function prepareEventForForm(Event $event): array
    {
        $tz = $event->timezone ?: 'UTC';
        $starts = Carbon::parse($event->starts_at)->timezone($tz);
        $ends = $event->ends_at ? Carbon::parse($event->ends_at)->timezone($tz) : null;

        return [
            'id' => $event->id,
            'slug' => $event->slug,
            'title' => $event->title,
            'excerpt' => $event->excerpt,
            'description' => $event->description ?? '',
            'location' => $event->location,
            'speaker_name' => $event->speaker_name,
            'timezone' => $tz,
            'all_day' => (bool) $event->all_day,
            'starts_at_local' => $starts->format('Y-m-d\TH:i'),
            'ends_at_local' => $ends ? $ends->format('Y-m-d\TH:i') : '',
            'start_date' => $event->all_day ? $starts->format('Y-m-d') : '',
            'end_date' => $event->all_day && $ends ? $ends->format('Y-m-d') : '',
            'recurrence_frequency' => $event->recurrence_frequency ?? 'none',
            'recurrence_on' => $event->recurrence_on ? $event->recurrence_on->format('Y-m-d') : '',
            'exclusion_dates' => $event->exclusion_dates ?? [],
            'featured_image' => $event->featured_image,
            'featured_image_url' => $event->featured_image_url,
        ];
    }
}
