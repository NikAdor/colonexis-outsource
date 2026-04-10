<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;

class CoursePurchaseController extends Controller
{
    public function store(Course $course): RedirectResponse
    {
        if (! SiteSetting::bool('courses_enabled')) {
            abort(404);
        }

        if (! $course->is_published) {
            abort(404);
        }

        $user = auth()->user();

        if ($user->coursePurchases()->where('course_id', $course->id)->exists()) {
            return redirect()->back()->with('info', 'You are already enrolled in this course.');
        }

        $paymentMethod = SiteSetting::string('payment_method', SiteSetting::PAYMENT_XENDIT);
        $paymentMethod = in_array($paymentMethod, [SiteSetting::PAYMENT_XENDIT, SiteSetting::PAYMENT_PAYMONGO], true)
            ? $paymentMethod
            : SiteSetting::PAYMENT_XENDIT;
        $gatewayKey = $paymentMethod === SiteSetting::PAYMENT_PAYMONGO
            ? SiteSetting::string('paymongo_secret_key')
            : SiteSetting::string('xendit_secret_key');

        if ((float) $course->price > 0 && ! $gatewayKey) {
            return redirect()->back()->with('info', 'Payment gateway is not configured yet. Ask admin to set the API key in Settings.');
        }

        $user->coursePurchases()->create([
            'course_id' => $course->id,
            'amount_paid' => $course->price,
        ]);

        $message = ((float) $course->price <= 0)
            ? 'You have enrolled in this free course.'
            : 'Purchase recorded via '.strtoupper($paymentMethod).'. You now have access to this course.';

        return redirect()->back()->with('success', $message);
    }
}
