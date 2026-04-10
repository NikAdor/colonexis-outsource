<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('excerpt')->nullable()->after('title');
            $table->string('featured_image')->nullable()->after('description');
            $table->string('speaker_name')->nullable()->after('location');
            $table->string('timezone')->default('UTC')->after('speaker_name');
            $table->boolean('all_day')->default(false)->after('timezone');
            $table->string('recurrence_frequency')->default('none')->after('all_day');
            $table->date('recurrence_on')->nullable()->after('recurrence_frequency');
            $table->json('exclusion_dates')->nullable()->after('recurrence_on');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'excerpt',
                'featured_image',
                'speaker_name',
                'timezone',
                'all_day',
                'recurrence_frequency',
                'recurrence_on',
                'exclusion_dates',
            ]);
        });
    }
};
