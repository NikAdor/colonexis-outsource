<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class PublicEventController extends Controller
{
    /**
     * Public event landing page (shareable URL). Not gated by the dashboard
     * "events" module toggle — that only controls the dashboard widget.
     */
    public function show(Event $event): Response
    {
        return Inertia::render('Event/Show', [
            'event' => $event->toPreviewPayload(),
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}
