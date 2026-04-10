<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Throwable;

class SiteSetting extends Model
{
    public const PAYMENT_XENDIT = 'xendit';

    public const PAYMENT_PAYMONGO = 'paymongo';

    /**
     * If set, used to verify Xendit webhooks via `X-CALLBACK-TOKEN`.
     */
    public const XENDIT_CALLBACK_TOKEN_KEY = 'xendit_callback_token';

    protected $fillable = [
        'key',
        'value',
    ];

    public static function bool(string $key, bool $default = false): bool
    {
        try {
            $raw = static::query()->where('key', $key)->value('value');
            if ($raw === null) {
                return $default;
            }
        } catch (Throwable) {
            return $default;
        }

        return $raw === '1' || $raw === 'true' || $raw === 1;
    }

    public static function setBool(string $key, bool $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value ? '1' : '0']
        );
    }

    public static function string(string $key, ?string $default = null): ?string
    {
        try {
            $raw = static::query()->where('key', $key)->value('value');
        } catch (Throwable) {
            return $default;
        }

        return $raw ?? $default;
    }

    public static function setString(string $key, string $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
