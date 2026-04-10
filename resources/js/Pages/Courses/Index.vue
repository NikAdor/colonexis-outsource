<script setup>
import MarketingLayout from '@/Components/MarketingLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    courses: {
        type: Array,
        default: () => [],
    },
    canLogin: { type: Boolean, default: false },
    canRegister: { type: Boolean, default: false },
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const page = usePage();

const searchQuery = ref('');
const sortBy = ref('relevant');
const expandedSection = ref('all');
const draftLevel = ref('');
const appliedLevel = ref('');
const visibleCount = ref(6);

const authed = computed(() => !!page.props.auth?.user);

function stripHtml(html) {
    if (!html) return '';
    return String(html).replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
}

function formatCoursePrice(price) {
    const n = Number(price);
    if (n <= 0) return 'Free';
    return new Intl.NumberFormat(undefined, { style: 'currency', currency: 'USD' }).format(n);
}

function formatDurationMinutes(minutes) {
    const m = Number(minutes);
    if (!m || m <= 0) return '—';
    const h = Math.round(m / 60);
    if (h < 1) return `${m} min`;
    return `${h} Hours`;
}

function courseSummary(course) {
    if (course.subtitle) return course.subtitle;
    if (course.excerpt) return course.excerpt;
    return stripHtml(course.description);
}

function levelLabel(level) {
    if (!level) return 'Course';
    return String(level).replace(/_/g, ' ');
}

function badgeVariant(level) {
    const l = String(level || '').toLowerCase();
    if (l === 'advanced') return 'advanced';
    if (l === 'intermediate') return 'intermediate';
    if (l === 'beginner') return 'beginner';
    return 'all';
}

const filteredCourses = computed(() => {
    let list = [...props.courses];

    const q = searchQuery.value.trim().toLowerCase();
    if (q) {
        list = list.filter((c) => {
            const hay = [c.title, c.subtitle, c.excerpt, stripHtml(c.description)].join(' ').toLowerCase();
            return hay.includes(q);
        });
    }

    if (appliedLevel.value) {
        list = list.filter((c) => String(c.level || '').toLowerCase() === appliedLevel.value);
    }

    if (sortBy.value === 'title') {
        list.sort((a, b) => String(a.title).localeCompare(String(b.title)));
    } else if (sortBy.value === 'price_asc') {
        list.sort((a, b) => Number(a.price) - Number(b.price));
    } else if (sortBy.value === 'price_desc') {
        list.sort((a, b) => Number(b.price) - Number(a.price));
    } else if (sortBy.value === 'newest') {
        list.sort((a, b) => {
            const ta = new Date(a.created_at || 0).getTime();
            const tb = new Date(b.created_at || 0).getTime();
            return tb - ta;
        });
    }

    return list;
});

const visibleCourses = computed(() => filteredCourses.value.slice(0, visibleCount.value));

const canLoadMore = computed(() => filteredCourses.value.length > visibleCount.value);

const totalShown = computed(() => filteredCourses.value.length);

const hasCatalog = computed(() => props.courses.length > 0);

onMounted(() => {
    draftLevel.value = appliedLevel.value;
});

function applyFilters() {
    appliedLevel.value = draftLevel.value;
    visibleCount.value = 6;
}

function selectCategoryTab() {
    expandedSection.value = 'all';
    draftLevel.value = '';
    appliedLevel.value = '';
    visibleCount.value = 6;
}

function toggleLevelSection() {
    if (expandedSection.value === 'level') {
        expandedSection.value = 'all';
    } else {
        expandedSection.value = 'level';
        draftLevel.value = appliedLevel.value;
    }
}

function loadMore() {
    visibleCount.value += 6;
}
</script>

<template>
    <MarketingLayout :can-login="canLogin" :can-register="canRegister">
        <div class="courses-index">
        <Head :title="`Browse Courses | ${appName}`" />

        <div class="courses-index__shell">
            <aside class="courses-index__aside" aria-label="Filters">
                <div class="courses-index__aside-head">
                    <Link href="/" class="courses-index__home">← Home</Link>
                    <h2 class="courses-index__aside-title">Filter Archive</h2>
                    <p class="courses-index__aside-sub">Refine Technical Selection</p>
                </div>

                <div class="courses-index__filter-list">
                    <button
                        type="button"
                        class="courses-index__filter-item"
                        :class="{ 'courses-index__filter-item--active': expandedSection === 'all' }"
                        @click="selectCategoryTab"
                    >
                        <span class="courses-index__filter-item-inner">
                            <span class="material-symbols-outlined">category</span>
                            <span>All Categories</span>
                        </span>
                        <span class="material-symbols-outlined">expand_more</span>
                    </button>

                    <div>
                        <button
                            type="button"
                            class="courses-index__filter-item"
                            :class="{ 'courses-index__filter-item--active': expandedSection === 'level' }"
                            @click="toggleLevelSection"
                        >
                            <span class="courses-index__filter-item-inner">
                                <span class="material-symbols-outlined">bar_chart</span>
                                <span>Difficulty Level</span>
                            </span>
                        </button>
                        <ul v-if="expandedSection === 'level'" class="courses-index__level-options">
                            <li class="courses-index__level-option">
                                <label>
                                    <input v-model="draftLevel" type="radio" name="level" value="" />
                                    Any level
                                </label>
                            </li>
                            <li class="courses-index__level-option">
                                <label>
                                    <input v-model="draftLevel" type="radio" name="level" value="beginner" />
                                    Beginner
                                </label>
                            </li>
                            <li class="courses-index__level-option">
                                <label>
                                    <input v-model="draftLevel" type="radio" name="level" value="intermediate" />
                                    Intermediate
                                </label>
                            </li>
                            <li class="courses-index__level-option">
                                <label>
                                    <input v-model="draftLevel" type="radio" name="level" value="advanced" />
                                    Advanced
                                </label>
                            </li>
                            <li class="courses-index__level-option">
                                <label>
                                    <input v-model="draftLevel" type="radio" name="level" value="all_levels" />
                                    All levels
                                </label>
                            </li>
                        </ul>
                    </div>

                    <div class="courses-index__filter-item courses-index__filter-item--static">
                        <span class="courses-index__filter-item-inner">
                            <span class="material-symbols-outlined">payments</span>
                            <span>Pricing Models</span>
                        </span>
                    </div>
                    <div class="courses-index__filter-item courses-index__filter-item--static">
                        <span class="courses-index__filter-item-inner">
                            <span class="material-symbols-outlined">star</span>
                            <span>Average Rating</span>
                        </span>
                    </div>
                    <div class="courses-index__filter-item courses-index__filter-item--static">
                        <span class="courses-index__filter-item-inner">
                            <span class="material-symbols-outlined">terminal</span>
                            <span>Tech Stack</span>
                        </span>
                    </div>
                </div>

                <div class="courses-index__aside-footer">
                    <button type="button" class="courses-index__apply" @click="applyFilters">Apply Filters</button>
                    <div class="courses-index__aside-links">
                        <Link :href="route('contact')" class="courses-index__aside-link">
                            <span class="material-symbols-outlined">help</span>
                            <span>Support</span>
                        </Link>
                        <Link
                            v-if="authed"
                            :href="route('profile.edit')"
                            class="courses-index__aside-link"
                        >
                            <span class="material-symbols-outlined">settings</span>
                            <span>Settings</span>
                        </Link>
                        <Link
                            v-else-if="canLogin"
                            :href="route('login')"
                            class="courses-index__aside-link"
                        >
                            <span class="material-symbols-outlined">settings</span>
                            <span>Sign in</span>
                        </Link>
                    </div>
                </div>
            </aside>

            <main id="main-content" class="courses-index__main">
                <div class="courses-index__toolbar">
                    <div>
                        <h1 class="courses-index__page-title">Curated Archives</h1>
                        <p class="courses-index__page-meta">
                            Displaying
                            <strong>{{ totalShown }}</strong>
                            technical module{{ totalShown === 1 ? '' : 's' }} found in
                            <strong>Systems Architecture</strong>
                        </p>
                        <div class="courses-index__search-wrap">
                            <span class="material-symbols-outlined courses-index__search-icon">search</span>
                            <input
                                v-model="searchQuery"
                                type="search"
                                class="courses-index__search-input"
                                placeholder="Search architecture..."
                                aria-label="Search courses"
                            />
                        </div>
                    </div>
                    <div class="courses-index__sort">
                        <span class="courses-index__sort-label">Sort By:</span>
                        <select v-model="sortBy" class="courses-index__sort-select" aria-label="Sort courses">
                            <option value="relevant">Most Relevant</option>
                            <option value="newest">Newest Release</option>
                            <option value="title">Title A–Z</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <div v-if="!totalShown" class="courses-index__empty">
                    <p v-if="!hasCatalog">No courses are available yet. Check back soon.</p>
                    <p v-else>No courses match your filters. Try clearing search or difficulty.</p>
                </div>

                <div v-else class="courses-index__grid">
                    <article v-for="course in visibleCourses" :key="course.id" class="courses-index__card">
                        <Link :href="route('courses.show', course.slug)" class="courses-index__card-media">
                            <img
                                v-if="course.featured_image_url"
                                class="courses-index__card-img"
                                :src="course.featured_image_url"
                                alt=""
                                width="640"
                                height="360"
                                loading="lazy"
                            />
                            <div v-else class="courses-index__card-placeholder">Course</div>
                            <span
                                v-if="course.level"
                                class="courses-index__card-badge"
                                :class="`courses-index__card-badge--${badgeVariant(course.level)}`"
                            >
                                {{ levelLabel(course.level) }}
                            </span>
                        </Link>
                        <div class="courses-index__card-body">
                            <div class="courses-index__card-head">
                                <Link :href="route('courses.show', course.slug)" class="courses-index__card-title">
                                    {{ course.title }}
                                </Link>
                                <button type="button" class="courses-index__bookmark" aria-label="Bookmark">
                                    <span class="material-symbols-outlined">bookmark</span>
                                </button>
                            </div>
                            <p class="courses-index__card-desc">{{ courseSummary(course) }}</p>
                            <div class="courses-index__author">
                                <div
                                    class="courses-index__author-avatar courses-index__author-avatar--ph"
                                    aria-hidden="true"
                                />
                                <div>
                                    <p class="courses-index__author-name">{{ appName }}</p>
                                    <p class="courses-index__author-role">Instructor team</p>
                                </div>
                            </div>
                            <div class="courses-index__card-foot">
                                <div class="courses-index__card-meta">
                                    <div class="courses-index__meta-item courses-index__meta-item--muted">
                                        <span class="material-symbols-outlined">schedule</span>
                                        <span>{{ formatDurationMinutes(course.duration_minutes) }}</span>
                                    </div>
                                </div>
                                <span class="courses-index__price">{{ formatCoursePrice(course.price) }}</span>
                            </div>
                        </div>
                    </article>
                </div>

                <div v-if="canLoadMore" class="courses-index__load-more">
                    <button type="button" class="courses-index__load-btn" @click="loadMore">
                        Load More Archives
                    </button>
                </div>
            </main>
        </div>

        <nav class="courses-index__mobile-nav" aria-label="Mobile section">
            <Link :href="route('courses.index')" class="is-active">
                <span class="material-symbols-outlined">explore</span>
                <span>Browse</span>
            </Link>
            <a href="#main-content">
                <span class="material-symbols-outlined">route</span>
                <span>Paths</span>
            </a>
            <a href="#main-content">
                <span class="material-symbols-outlined">workspace_premium</span>
                <span>Certs</span>
            </a>
            <Link v-if="authed" :href="route('dashboard')">
                <span class="material-symbols-outlined">account_circle</span>
                <span>Profile</span>
            </Link>
            <Link v-else-if="canLogin" :href="route('login')">
                <span class="material-symbols-outlined">account_circle</span>
                <span>Profile</span>
            </Link>
            <Link v-else href="/">
                <span class="material-symbols-outlined">account_circle</span>
                <span>Home</span>
            </Link>
        </nav>
        </div>
    </MarketingLayout>
</template>

<style lang="scss" src="../../../scss/courses-index.scss"></style>
