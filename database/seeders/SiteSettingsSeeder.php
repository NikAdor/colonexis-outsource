<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::setBool('events_enabled', false);
        SiteSetting::setBool('courses_enabled', false);
        SiteSetting::setString('payment_method', SiteSetting::PAYMENT_XENDIT);
    }
}
