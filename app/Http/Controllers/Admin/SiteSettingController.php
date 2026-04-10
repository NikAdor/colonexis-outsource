<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SiteSettingController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Admin/Settings', [
            'events_enabled' => SiteSetting::bool('events_enabled'),
            'courses_enabled' => SiteSetting::bool('courses_enabled'),
            'payment_method' => SiteSetting::string('payment_method', SiteSetting::PAYMENT_XENDIT),
            'xendit_key_configured' => (bool) SiteSetting::string('xendit_secret_key'),
            'xendit_callback_configured' => (bool) SiteSetting::string(SiteSetting::XENDIT_CALLBACK_TOKEN_KEY),
            'paymongo_key_configured' => (bool) SiteSetting::string('paymongo_secret_key'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'events_enabled' => 'sometimes|boolean',
            'courses_enabled' => 'sometimes|boolean',
            'payment_method' => 'required|in:xendit,paymongo',
            'xendit_secret_key' => 'nullable|string|max:255',
            'xendit_callback_token' => 'nullable|string|max:255',
            'paymongo_secret_key' => 'nullable|string|max:255',
        ]);

        SiteSetting::setBool('events_enabled', $request->boolean('events_enabled'));
        SiteSetting::setBool('courses_enabled', $request->boolean('courses_enabled'));
        SiteSetting::setString('payment_method', $request->string('payment_method')->toString());
        if ($request->filled('xendit_secret_key')) {
            SiteSetting::setString('xendit_secret_key', $request->string('xendit_secret_key')->toString());
        }
        if ($request->filled('xendit_callback_token')) {
            SiteSetting::setString(SiteSetting::XENDIT_CALLBACK_TOKEN_KEY, $request->string('xendit_callback_token')->toString());
        }
        if ($request->filled('paymongo_secret_key')) {
            SiteSetting::setString('paymongo_secret_key', $request->string('paymongo_secret_key')->toString());
        }

        return redirect()->route('admin.settings.edit')->with('success', 'Site features updated.');
    }
}
