<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('title');
            $table->string('excerpt')->nullable();
            $table->json('learning_outcomes')->nullable();
            $table->json('requirements')->nullable();
            $table->string('level')->default('beginner');
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->string('language')->default('English');
            $table->string('featured_image')->nullable();
        });

        Schema::create('course_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained('course_sections')->cascadeOnDelete();
            $table->string('title');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->text('body')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_lessons');
        Schema::dropIfExists('course_sections');

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'subtitle',
                'excerpt',
                'learning_outcomes',
                'requirements',
                'level',
                'duration_minutes',
                'language',
                'featured_image',
            ]);
        });
    }
};
