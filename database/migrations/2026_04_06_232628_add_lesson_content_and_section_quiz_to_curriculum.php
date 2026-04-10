<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_sections', function (Blueprint $table) {
            $table->boolean('quiz_enabled')->default(false)->after('sort_order');
            $table->string('quiz_title')->nullable()->after('quiz_enabled');
            $table->json('quiz_questions')->nullable()->after('quiz_title');
        });

        Schema::table('course_lessons', function (Blueprint $table) {
            $table->string('content_type')->default('text')->after('sort_order');
            $table->string('video_url')->nullable()->after('content_type');
            $table->string('image_path')->nullable()->after('video_url');
            $table->string('pdf_path')->nullable()->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('course_lessons', function (Blueprint $table) {
            $table->dropColumn(['content_type', 'video_url', 'image_path', 'pdf_path']);
        });

        Schema::table('course_sections', function (Blueprint $table) {
            $table->dropColumn(['quiz_enabled', 'quiz_title', 'quiz_questions']);
        });
    }
};
