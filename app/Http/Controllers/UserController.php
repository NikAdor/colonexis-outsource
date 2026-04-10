<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePurchase;
use App\Models\Event;
use App\Models\SiteSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class UserController extends Controller
{
    public function dashboard()
    {
        $users = null;

        if (auth()->user()->isAdmin()) {
            $users = User::select('id', 'name', 'email', 'role', 'created_at')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        $siteSettings = [
            'events_enabled' => SiteSetting::bool('events_enabled'),
            'courses_enabled' => SiteSetting::bool('courses_enabled'),
        ];

        $upcomingEvents = [];
        if ($siteSettings['events_enabled']) {
            $upcomingEvents = Event::query()
                ->where('starts_at', '>=', now()->startOfDay())
                ->orderBy('starts_at')
                ->limit(8)
                ->get();
        }

        $catalogCourses = [];
        $purchasedCourseIds = [];
        if ($siteSettings['courses_enabled']) {
            $catalogCourses = Course::query()
                ->where('is_published', true)
                ->orderBy('title')
                ->get();
            $purchasedCourseIds = auth()->user()
                ->coursePurchases()
                ->where('status', CoursePurchase::STATUS_PAID)
                ->pluck('course_id')
                ->all();
        }

        $adminOverview = null;
        if (auth()->user()->isAdmin()) {
            $adminOverview = $this->buildAdminOverview();
        }

        return Inertia::render('Dashboard', [
            'users' => $users,
            'siteSettings' => $siteSettings,
            'upcomingEvents' => $upcomingEvents,
            'catalogCourses' => $catalogCourses,
            'purchasedCourseIds' => $purchasedCourseIds,
            'adminOverview' => $adminOverview,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildAdminOverview(): array
    {
        $totalRevenue = (float) CoursePurchase::query()
            ->where('status', CoursePurchase::STATUS_PAID)
            ->sum('amount_paid');
        $totalStudents = User::query()->where('role', User::ROLE_CLIENT)->count();
        $enrolledClients = User::query()
            ->where('role', User::ROLE_CLIENT)
            ->whereHas('coursePurchases')
            ->count();
        $activeCourses = Course::query()->where('is_published', true)->count();
        $purchaseCount = CoursePurchase::query()->where('status', CoursePurchase::STATUS_PAID)->count();
        $enrollmentRate = $totalStudents > 0
            ? round(($enrolledClients / $totalStudents) * 100, 1)
            : 0.0;
        $coursesPerUser = $totalStudents > 0
            ? round($purchaseCount / $totalStudents, 1)
            : 0.0;

        $revenueChart = [];
        $monthTotals = [];
        for ($i = 9; $i >= 0; $i--) {
            $m = now()->subMonths($i)->startOfMonth();
            $key = $m->format('Y-m');
            $sum = (float) CoursePurchase::query()
                ->whereYear('created_at', $m->year)
                ->whereMonth('created_at', $m->month)
                ->where('status', CoursePurchase::STATUS_PAID)
                ->sum('amount_paid');
            $monthTotals[$key] = $sum;
        }
        $maxMonth = max($monthTotals) ?: 1.0;
        foreach ($monthTotals as $ym => $total) {
            $d = Carbon::createFromFormat('Y-m', $ym);
            $actualPct = (int) round(($total / $maxMonth) * 100);
            $actualPct = $total > 0 ? max(18, $actualPct) : 12;
            $projectedPct = min(100, $actualPct + (int) round($actualPct * 0.15) + 5);
            $revenueChart[] = [
                'label' => $d->format('M'),
                'actualPct' => $actualPct,
                'projectedPct' => $projectedPct,
            ];
        }

        $recentActivity = CoursePurchase::query()
            ->where('status', CoursePurchase::STATUS_PAID)
            ->with(['user:id,name', 'course:id,title,slug'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (CoursePurchase $p) => [
                'type' => 'enrollment',
                'icon' => 'person_add',
                'iconTone' => 'tertiary',
                'title' => 'New student enrolled',
                'body' => ($p->user->name ?? 'Someone').' joined '.($p->course->title ?? 'a course'),
                'time' => $p->created_at?->diffForHumans() ?? '',
            ])
            ->all();

        $topCourses = Course::query()
            ->withCount(['purchases' => fn ($q) => $q->where('status', CoursePurchase::STATUS_PAID)])
            ->withSum(['purchases as purchases_sum_amount_paid' => fn ($q) => $q->where('status', CoursePurchase::STATUS_PAID)], 'amount_paid')
            ->orderByDesc('purchases_count')
            ->orderBy('title')
            ->limit(5)
            ->get()
            ->map(fn (Course $c) => [
                'id' => $c->id,
                'title' => $c->title,
                'slug' => $c->slug,
                'students' => (int) $c->purchases_count,
                'revenue' => (float) ($c->purchases_sum_amount_paid ?? 0),
                'status' => $this->coursePerformanceStatus($c),
            ])
            ->all();

        $maxEnrollments = max(1, (int) collect($topCourses)->max(fn (array $c) => $c['students']));
        $topCoursesWithPct = array_map(function (array $c) use ($maxEnrollments) {
            $c['enrollmentPct'] = (int) round(($c['students'] / $maxEnrollments) * 100);

            return $c;
        }, $topCourses);

        $recentEnrollments = CoursePurchase::query()
            ->where('status', CoursePurchase::STATUS_PAID)
            ->with(['user:id,name,email', 'course:id,title'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (CoursePurchase $p) => [
                'studentName' => $p->user->name ?? '—',
                'studentEmail' => $p->user->email ?? '',
                'courseTitle' => $p->course->title ?? '—',
                'value' => (float) $p->amount_paid,
                'status' => 'success',
            ])
            ->all();

        return [
            'totalRevenue' => $totalRevenue,
            'totalStudents' => $totalStudents,
            'activeCourses' => $activeCourses,
            'totalEnrollments' => $purchaseCount,
            'enrollmentRate' => $enrollmentRate,
            'coursesPerUser' => $coursesPerUser,
            'revenueChart' => $revenueChart,
            'recentActivity' => $recentActivity,
            'topCourses' => $topCoursesWithPct,
            'recentEnrollments' => $recentEnrollments,
        ];
    }

    private function coursePerformanceStatus(Course $course): string
    {
        if ($course->created_at && $course->created_at->gt(now()->subDays(30))) {
            return 'new';
        }
        if ((int) $course->purchases_count >= 3) {
            return 'trending';
        }

        return 'stable';
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $query = User::query()
            ->select('id', 'name', 'email', 'role', 'created_at', 'email_verified_at')
            ->with(['coursePurchases' => fn ($q) => $q->with('course:id,title')])
            ->orderBy('created_at', 'desc');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $users = $query->paginate(12)->withQueryString();

        $totalStudents = User::query()->where('role', User::ROLE_CLIENT)->count();
        $enrolledClients = User::query()
            ->where('role', User::ROLE_CLIENT)
            ->whereHas('coursePurchases')
            ->count();
        $avgProgress = $totalStudents > 0
            ? round(($enrolledClients / $totalStudents) * 100, 1)
            : 0.0;

        $multiCourseClients = User::query()
            ->where('role', User::ROLE_CLIENT)
            ->whereIn('id', function ($q) {
                $q->select('user_id')
                    ->from('course_purchases')
                    ->groupBy('user_id')
                    ->havingRaw('count(*) >= 2');
            })
            ->count();
        $completionRate = $totalStudents > 0
            ? (int) round(($multiCourseClients / $totalStudents) * 100)
            : 0;

        $activeNowEstimate = $totalStudents > 0
            ? max(1, (int) round($totalStudents * 0.27))
            : 0;

        return Inertia::render('Users/Index', [
            'users' => $users,
            'filters' => [
                'search' => $search,
            ],
            'stats' => [
                'total_students' => $totalStudents,
                'total_directory' => User::query()->count(),
                'avg_progress' => $avgProgress,
                'active_now' => min($totalStudents, $activeNowEstimate),
                'completion_rate' => $completionRate,
                'completion_target' => 30,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:client,staff,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class.',email,'.$user->id,
            'role' => 'required|in:client,staff,admin',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
