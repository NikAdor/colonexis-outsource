<?php

use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\CoursePurchaseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicCourseController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

$marketingProps = fn () => [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
];

Route::get('/about', fn () => Inertia::render('About', $marketingProps()))->name('about');
Route::get('/services', fn () => Inertia::render('Services', $marketingProps()))->name('services');
Route::get('/works', fn () => Inertia::render('Works', $marketingProps()))->name('works');
Route::get('/testimonials', fn () => Inertia::render('Testimonials', $marketingProps()))->name('testimonials');
Route::get('/contact', fn () => Inertia::render('Contact', $marketingProps()))->name('contact');

Route::get('/courses', [PublicCourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course:slug}', [PublicCourseController::class, 'show'])->name('courses.show');

Route::get('/event/{event:slug}', [PublicEventController::class, 'show'])->name('events.show');
Route::redirect('/events/{slug}', '/event/{slug}', 301);

Route::get('/dashboard', [UserController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/courses/{course}/purchase', [CoursePurchaseController::class, 'store'])->name('courses.purchase');
    Route::get('/inbox', function () {
        $user = auth()->user();
        abort_unless($user && ($user->isAdmin() || $user->isStaff()), 403);

        return Inertia::render('Inbox');
    })->name('inbox');
    Route::get('/help', fn () => Inertia::render('Help'))->name('help');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
            Route::patch('settings', [SiteSettingController::class, 'update'])->name('settings.update');
            Route::get('events/{event}/preview', [AdminEventController::class, 'preview'])->name('events.preview');
            Route::resource('events', AdminEventController::class)->except(['show']);
            Route::resource('courses', AdminCourseController::class)->except(['show']);
        });
    });
});

require __DIR__.'/auth.php';
