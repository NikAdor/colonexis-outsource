<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePurchase;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

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

        $existing = $user->coursePurchases()->where('course_id', $course->id)->first();
        if ($existing && $existing->status === CoursePurchase::STATUS_PAID) {
            return redirect()
                ->route('courses.learn', ['course' => $course->slug])
                ->with('info', 'You are already enrolled in this course.');
        }
        if ($existing && $existing->status === CoursePurchase::STATUS_PENDING && $existing->checkout_url) {
            return redirect()
                ->away($existing->checkout_url)
                ->with('info', 'Payment is still pending. Redirecting you to checkout.');
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

        // Free course: grant access immediately.
        if ((float) $course->price <= 0) {
            $user->coursePurchases()->updateOrCreate(
                ['course_id' => $course->id],
                [
                    'amount_paid' => 0,
                    'status' => CoursePurchase::STATUS_PAID,
                    'payment_method' => $paymentMethod,
                    'provider' => null,
                    'provider_reference' => null,
                    'checkout_url' => null,
                ]
            );

            return redirect()
                ->route('courses.learn', ['course' => $course->slug])
                ->with('success', 'You have enrolled in this free course.');
        }

        // Paid course: create Xendit invoice and redirect to checkout.
        if ($paymentMethod === SiteSetting::PAYMENT_XENDIT) {
            $externalId = sprintf('course_%d_user_%d_%s', $course->id, $user->id, now()->format('YmdHis'));
            $successUrl = route('courses.learn', ['course' => $course->slug]);
            $failureUrl = route('courses.show', ['course' => $course->slug]);

            $resp = Http::withBasicAuth($gatewayKey, '')
                ->acceptJson()
                ->asJson()
                ->post('https://api.xendit.co/v2/invoices', [
                    'external_id' => $externalId,
                    'amount' => (float) $course->price,
                    'payer_email' => $user->email,
                    'description' => 'Course access: '.$course->title,
                    'success_redirect_url' => $successUrl,
                    'failure_redirect_url' => $failureUrl,
                ]);

            if (! $resp->successful()) {
                return redirect()
                    ->back()
                    ->with('info', 'Xendit invoice creation failed. Please verify your API key and try again.');
            }

            $invoice = $resp->json();
            $invoiceUrl = $invoice['invoice_url'] ?? null;

            $purchase = $user->coursePurchases()->updateOrCreate(
                ['course_id' => $course->id],
                [
                    'amount_paid' => (float) $course->price,
                    'status' => CoursePurchase::STATUS_PENDING,
                    'payment_method' => $paymentMethod,
                    'provider' => 'xendit',
                    'provider_reference' => $externalId,
                    'checkout_url' => $invoiceUrl,
                ]
            );

            if (! $purchase->checkout_url) {
                return redirect()
                    ->back()
                    ->with('info', 'Xendit did not return an invoice URL. Please try again.');
            }

            return redirect()->away($purchase->checkout_url);
        }

        return redirect()->back()->with('info', 'Payment method is not supported yet.');
    }
}
