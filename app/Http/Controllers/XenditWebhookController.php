<?php

namespace App\Http\Controllers;

use App\Models\CoursePurchase;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class XenditWebhookController extends Controller
{
    public function invoice(Request $request): Response
    {
        // Optional verification (recommended). If configured, require a matching callback token.
        $expectedToken = SiteSetting::string('xendit_callback_token');
        if ($expectedToken) {
            $token = (string) $request->header('X-CALLBACK-TOKEN');
            abort_unless(hash_equals($expectedToken, $token), 401);
        }

        $payload = $request->all();
        $externalId = (string) ($payload['external_id'] ?? '');
        $status = strtoupper((string) ($payload['status'] ?? ''));

        if (! $externalId) {
            return response('missing external_id', 422);
        }

        $purchase = CoursePurchase::query()
            ->where('provider', 'xendit')
            ->where('provider_reference', $externalId)
            ->first();

        if (! $purchase) {
            return response('not found', 404);
        }

        if ($status === 'PAID' || $status === 'SETTLED') {
            $purchase->status = CoursePurchase::STATUS_PAID;
            $purchase->amount_paid = (float) ($payload['amount'] ?? $purchase->amount_paid);
            $purchase->save();
        }

        if ($status === 'EXPIRED') {
            $purchase->status = 'expired';
            $purchase->save();
        }

        return response('ok', 200);
    }
}
