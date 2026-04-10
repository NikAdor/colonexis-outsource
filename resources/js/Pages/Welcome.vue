<script setup>
import MarketingFooter from '@/Components/MarketingFooter.vue';
import MarketingHeader from '@/Components/MarketingHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canLogin: { type: Boolean, default: true },
    canRegister: { type: Boolean, default: true },
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const courseSearch = ref('');
const newsletterEmail = ref('');
const newsletterDone = ref(false);
const newsletterBusy = ref(false);
const testimonialTrack = ref(null);

const heroImage =
    'https://lh3.googleusercontent.com/aida-public/AB6AXuB8KvBbabqRpCTdsNN-xGt5JyOVQsFDaQ_H7wN7PskCrzjmPh7cOc32HcQisZkav9vPs_4Pj4VhOrQ_I40l4_6J6bFl29mpg2mS7yEnwethj5FYywwyJauWfgzIWRuJiq7mLe-c1gTg9yL6crjZAY1O_30kLpOxChe1-YHEU4onhM6wdf-pfVNcbbAdI_ivJFYSeSm3ec1Zaw0etvF81uS8CelerRaoGc6KZadP0ap_p5-6qdEF5Huy8-K0qkkl6Vc9MLlGwcLgdZA';

const whyImages = {
    a: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCxwh5sQRqIDJvecxDW_5sWFIGmjFcWbBdMTlUyR7MeP832qIqSAzHNdSs7gH0EKgC5w_fKq4WVBTBz67pTZ1idQXWMDm5bq1VOSN8o9V_2lXh7_UA0X-3ehqd8IsCkb3XLjoCgm7JSup2PDQqqm50oRZekpfURBsNNGT0bkB_YujnPLYERRw9Q1GZtNMpv3fIEvw7HdsL478ZEsEU86uM7uMaB9yj214xM0IweOIPti4zttqOfzxeyG4Atk7uVuS05p6pf7WX_AIg',
    b: 'https://lh3.googleusercontent.com/aida-public/AB6AXuD59t87PWEi_nuoSgkIJEm73yMD08cq2kJWVZoQC3gAJvAfCtqlBtiIzwZVx1iYNXt0LSFpqtlMIFuSQHSByXDmugohd0hRa1oR2aWDn1g1KwR0qh4nQaB0Kad2SrwSw5zHWqpZj2HhkffXZTgEBAqQ9umx7lnaM7XZ6gx5Pr2mziy-hjaV3lWUVaT8lJ5m4FivwdQszm8o-RYGu5BOsEsaWdbuXQYx9B9KVXZiyr97w_GlH1yn7WyUMwJoRGSBeOXJRy-ni7Qga_s',
};

const categories = [
    { icon: 'code', label: 'Software Dev' },
    { icon: 'architecture', label: 'System Design' },
    { icon: 'cloud', label: 'Cloud Arch' },
    { icon: 'security', label: 'Cybersecurity' },
    { icon: 'groups', label: 'Leadership' },
    { icon: 'database', label: 'Big Data' },
];

const featuredCourses = [
    {
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBOy-Zy1skFuK5xOE5Q10qDTRJ_PXDN1DHmLHURhcv8UH4JSAIfqzUHRm20f_SOkJxCssLIvVYogRvqqGT7H7aGbqy9d2sq2-cxNrGrcgzmWrTBh4ZaS7rup9d1AywWTdbn1BAMki0BOe7VizCdBVJw_CceDgav25vtoOlaAU1j7XmQ-c4UB-okgMDlquiP2L3LGU3E9T5-75Uh2qmH09Jr0E3aH9UOGihOqerBLmZtGLsLPpdR22zD5E4ZVSAOIGB8JNSEkS4bh9M',
        tag: 'Engineering',
        tagVariant: 'primary',
        stars: ['full', 'full', 'full', 'full', 'full'],
        rating: '4.9/5',
        title: 'Advanced Distributed Systems & Microservices',
        instructorImg:
            'https://lh3.googleusercontent.com/aida-public/AB6AXuCv0uiu3UXkwwAElc-Sr-LWkqAI92Btm9KUwdahUpFW0Eycw_457tXF3AjDdVCjDnPfj41xn8AP2UHPxCojpBrQiNiDvC7S7rWtKekZx7P7_6ufDnitwAGyUfUGeI100EEdTKGsE0YB_QNH3M-WHt_ZF7s2PU23YzUavUScgoSIhbcUbUiklaSxEKPUlVo57oaY8p11WYRzOqDOXeYyjL3_-WC1_qYlS90xqbNAVP0LxYZQB0kNGV495w6kyxmwSQtXMQFTOvoA5nY',
        instructor: 'Dr. Julian Vance',
        price: '$199.00',
        hours: '42 Hours',
    },
    {
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDtIp0mA8O-jg6BmQ0y6u70EDJ8Lnlew-Wu5htWbssbx5cXV72A4pQzlnc_5Vj0E6StnENA-XdjqWBRrtjI_wAgSssmELYNVt4R-dhpxsiU_PgYbCdrm-T-5aTz26oeSL52V1Jq99nCKjTe4cfcDdD-P2-pvZPO4-JF4xEnyMpF8JjmKCGO_RKGARhFvatbS75RR7d5OK_UtZ676NdDHYhbhFJ5qIxjVz4hsWR4BpYoqhHGUbnyMueEX1Yg141huwY6abCGvrL9BMY',
        tag: 'Leadership',
        tagVariant: 'secondary',
        stars: ['full', 'full', 'full', 'full', 'half'],
        rating: '4.7/5',
        title: 'CTO Blueprint: Navigating Technical Debt',
        instructorImg:
            'https://lh3.googleusercontent.com/aida-public/AB6AXuAY7rG2vW0JMpUaW-5PTMopP3tl0IXDYV5_ITeRRYVMilWGCyNwUlyhc708H033u_ihl85GzwcQnPAmt0oleHAxaZ3AnjVRx532sGVMGtEvh9f7GLhTkr3-wok9TSTXBx_Js09ejQN11LwsDIKp75bkfpT3BNCrVIOvBq3caFTv1pwL1vsbgc-7GbUP9NpD0r2AqWXWkbxSJJN3x8J-1axUrssslNRvbT5dl2ycYu0gmKGh8yJ64gRnq86F3ZMSIJWIfDahCaqpRX0',
        instructor: 'Sarah Chen',
        price: '$149.00',
        hours: '28 Hours',
    },
    {
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBrzMmqcVGjdsXZME3rR1XqlXX_CqC1vJCxhmZe80Ug385ln4Xb0UZPXFvuDnWLnlLeOSEcVkTYn-I2UkRybPRG12jgZkt9zxBAAUk2vuQ4aekUqPnzt8f40Gk7hf09H24XzcXX78u_AQPWOu3HwZpUgDPq2wyNtHWNWyM_JOmE15ASYbg7QxsRJrgWY_BV-2z-2cpNcm_GzHkk1tvNwFz7KVp1FN8eo4zUoSqvIFDbXXPNemgwNFQLm4DfSrpPcfHFMN4KZn-aoOg',
        tag: 'Innovation',
        tagVariant: 'accent',
        stars: ['full', 'full', 'full', 'full', 'full'],
        rating: '5.0/5',
        title: 'Enterprise AI Implementation Strategies',
        instructorImg:
            'https://lh3.googleusercontent.com/aida-public/AB6AXuCD59YWSffgdaLWarzZY4A5u_lK0efX8ZmkKDgiJsgTftboZpJuLhEkZMQF33NTR3bKbBPSs-byd4PXWhBJ9dzQh7M3TMKzb8T9qJh2QfJSzCyPxhRgED6xfLVL-ZOV3qYhhK4G1cUkICz3R7HhN6WyEqtUGssH1nte0a7JQkpkNUKCPYX0_hjD1w0bPSMi64VG7xPPVBJfyrW5_pmBKddVm904q-telufN6tJ2Od4MY--M0ktY9bgliQN6KM5Y-mfVb5Be0A_NN3c',
        instructor: 'Elena Rodriguez',
        price: '$299.00',
        hours: '54 Hours',
    },
];

const instructors = [
    {
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBSTkg-j-bTm0g2VmIH5S_405GmlqpA9RLiFUgjM3OeYYyvVt7nAhTofXNpY97lqCZJsAOgoVsiqkW1ENLpQw1o4dqnbGw3fbvxwdukXzUNsB72hi2wiPpVWP67618a24azd8ZlG1dB0vJHg7-FmfwyJoizbhgZHmE-sBQGw7Xzdlk_MycNE9cbA-KuEU61I5l2Att9D84snTz4fCX6_v9vep8raupuP85fd33O7Mivsez31PfcimnzyC_oXBqcFpJ7zIsCjmVJHbg',
        name: 'Marcus Thorne',
        role: 'Ex-Google Staff Engineer',
    },
    {
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuASt30mt31X0iURsGayggwLlZTmhr5hzacp7HI52bH87ZMung02AoNTTD2FJUQ9tnIt3uNJzGd5vum6dF_KB8-NUwCwuhOG_uOs1VFsfwuSRhpzxjH14zaMYEIzwAECEsRkyu7C2p-UbIEe8XT1mUoVuu9cr6UShxi9jTqMUrlSEMG_9JBJXFcRQ5CumhbbwbZ7WK13lWQJru0r99F7JEPosJtHKnfyChCD3NtTJIk5WJL6tArq7St_4K1vPvF_3U-g6AhMnuvndaI',
        name: 'Leila Vance',
        role: 'Distinguished Architect at Stripe',
    },
    {
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBdMywkGjDysL_BDhrxn-DTti96A-g2E5q3LPPqpwjWZIYYzahSmq0FQhP_lRsOnmba2rTf2hi5gJVCaO9KmC6QAeyMLlvjO49uRhdUZyNO2tCnm90n1KzNIXnc82rrVXrFufq1X_5EK5rtIqb6WgqrVOE2KHXORdMDpC_sWDz0C8YE0owFPcyDkj-gsBfZxGtXNkdMy_qn8NJmV_L3LjpkElInRJRc_yfnk8PDqH5awlhrbl-lRkHfipn0T3-ExgEFPA6dX2R6Et0',
        name: 'Jameson Burke',
        role: 'Cloud Infrastructure Lead',
    },
    {
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBGe5fZFmrDaq02ux6ggCsaYGtH5d6kl3z3Y9cWu9xIKOq8_lb8zlNoDNE8UQg4rkcyV3s3u2mI5MiXqlYTiMHLEyYwK-SVpYZ1pgJNtDGEHNvgyiprUC_RhRRNGnd99FM21m1oBfAFcvtBqbHElUfamjzEWigptLV0puein9cMbEUaFay5oVkQpB2-2sfVALsSV59VV8G7xHhpOeXPgvRvZ4B6abrAtOQJqlPDODUdFF7hJkJThRZgmVbsFj1sZGjMJd9I_QlXxmo',
        name: 'Sonia Gupta',
        role: 'VP of Engineering at Datadog',
    },
];

const testimonials = [
    {
        quote: 'The System Design course here is on another level. It helped me land a Staff Engineer role at a top-tier fintech company. Highly recommended!',
        name: 'Alex Rivera',
        role: 'Staff Engineer',
    },
    {
        quote: 'The consulting sessions saved our migration project months of rework. Their architectural precision is unmatched in the industry.',
        name: 'Markus Webb',
        role: 'CTO, Nexus Corp',
    },
    {
        quote: "Curated engineering knowledge that you just can't find elsewhere. This platform is my secret weapon for staying ahead.",
        name: 'Julia Grant',
        role: 'Cloud Architect',
    },
];

const findCourses = () => {
    const q = courseSearch.value.trim();
    router.visit(route('courses.index', q ? { search: q } : {}));
};

const scrollTestimonials = (dir) => {
    const el = testimonialTrack.value;
    if (!el) return;
    el.scrollBy({ left: dir * Math.min(360, el.clientWidth * 0.85), behavior: 'smooth' });
};

const submitNewsletter = async () => {
    if (!newsletterEmail.value.trim()) return;
    newsletterBusy.value = true;
    await new Promise((r) => setTimeout(r, 800));
    newsletterBusy.value = false;
    newsletterDone.value = true;
    newsletterEmail.value = '';
};
</script>

<template>
    <div class="welcome-page">
        <Head :title="`${appName} — Master the Technical Frontier`">
            <link rel="preconnect" href="https://fonts.googleapis.com" />
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="" />
            <link
                href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap"
                rel="stylesheet"
            />
            <link
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap"
                rel="stylesheet"
            />
        </Head>

        <MarketingHeader :can-login="canLogin" :can-register="canRegister" />

        <main id="main-content" class="welcome-page__main">
            <section class="welcome-page__hero" aria-labelledby="welcome-hero-heading">
                <div class="welcome-page__hero-inner">
                    <div class="welcome-page__hero-copy">
                        <span class="welcome-page__eyebrow">Premium Engineering Education</span>
                        <h1 id="welcome-hero-heading" class="welcome-page__hero-title">
                            Build Smarter. <br />
                            Architect <span class="welcome-page__hero-accent">Higher.</span>
                        </h1>
                        <p class="welcome-page__hero-lead">
                            Master enterprise architecture and system design with curated curricula from the
                            industry's elite technical leaders.
                        </p>
                        <div class="welcome-page__search-bar">
                            <span class="material-symbols-outlined" aria-hidden="true">search</span>
                            <input
                                v-model="courseSearch"
                                class="welcome-page__search-input"
                                type="search"
                                placeholder="What technical skill do you want to master today?"
                                aria-label="Search courses"
                                @keydown.enter.prevent="findCourses"
                            />
                            <button type="button" class="welcome-page__search-btn" @click="findCourses">
                                Find Courses
                            </button>
                        </div>
                        <div class="welcome-page__hero-stats">
                            <div>
                                <div class="welcome-page__hero-stat-val">500+</div>
                                <div class="welcome-page__hero-stat-label">Specialized Courses</div>
                            </div>
                            <div>
                                <div class="welcome-page__hero-stat-val">25k+</div>
                                <div class="welcome-page__hero-stat-label">Active Students</div>
                            </div>
                            <div>
                                <div class="welcome-page__hero-stat-val">98%</div>
                                <div class="welcome-page__hero-stat-label">Success Rate</div>
                            </div>
                        </div>
                    </div>
                    <div class="welcome-page__hero-visual">
                        <div class="welcome-page__hero-blur" aria-hidden="true" />
                        <div class="welcome-page__hero-img-wrap">
                            <img
                                class="welcome-page__hero-img"
                                :src="heroImage"
                                alt=""
                                width="800"
                                height="600"
                                loading="eager"
                                decoding="async"
                            />
                        </div>
                        <div class="welcome-page__hero-badge">
                            <div class="welcome-page__hero-badge-row">
                                <span class="material-symbols-outlined" aria-hidden="true">verified_user</span>
                                <div>
                                    <div class="welcome-page__hero-badge-title">12+ Years</div>
                                    <div class="welcome-page__hero-badge-sub">Industry Excellence</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="welcome-page__section welcome-page__section--bg" aria-labelledby="browse-heading">
                <div class="welcome-page__container">
                    <div class="welcome-page__section-head">
                        <h2 id="browse-heading" class="welcome-page__h2">Browse by Specialization</h2>
                        <p class="welcome-page__section-desc">
                            Focused learning paths designed for high-growth engineering roles.
                        </p>
                    </div>
                    <div class="welcome-page__categories">
                        <Link
                            v-for="c in categories"
                            :key="c.label"
                            :href="route('courses.index')"
                            class="welcome-page__cat-card"
                        >
                            <div class="welcome-page__cat-icon-wrap">
                                <span class="material-symbols-outlined welcome-page__cat-icon">{{ c.icon }}</span>
                            </div>
                            <h4 class="welcome-page__cat-title">{{ c.label }}</h4>
                        </Link>
                    </div>
                </div>
            </section>

            <section class="welcome-page__section welcome-page__section--white" aria-labelledby="popular-heading">
                <div class="welcome-page__container">
                    <div class="welcome-page__row-title">
                        <div>
                            <h2 id="popular-heading" class="welcome-page__h2 welcome-page__h2--lg">
                                Most Popular Courses
                            </h2>
                            <p class="welcome-page__section-desc welcome-page__section-head--left">
                                Advanced engineering tracks for serious professionals.
                            </p>
                        </div>
                        <Link :href="route('courses.index')" class="welcome-page__link-arrow">
                            Explore All
                            <span class="material-symbols-outlined">trending_flat</span>
                        </Link>
                    </div>
                    <div class="welcome-page__course-grid">
                        <article
                            v-for="course in featuredCourses"
                            :key="course.title"
                            class="welcome-page__course-card"
                        >
                            <div class="welcome-page__course-media">
                                <img
                                    class="welcome-page__course-img"
                                    :src="course.image"
                                    alt=""
                                    width="640"
                                    height="320"
                                    loading="lazy"
                                />
                                <span
                                    class="welcome-page__course-tag"
                                    :class="`welcome-page__course-tag--${course.tagVariant}`"
                                >
                                    {{ course.tag }}
                                </span>
                            </div>
                            <div class="welcome-page__course-body">
                                <div class="welcome-page__stars">
                                    <template v-for="(s, i) in course.stars" :key="i">
                                        <span class="material-symbols-outlined">{{
                                            s === 'half' ? 'star_half' : 'star'
                                        }}</span>
                                    </template>
                                    <span class="welcome-page__course-rating">({{ course.rating }})</span>
                                </div>
                                <h3 class="welcome-page__course-title">{{ course.title }}</h3>
                                <div class="welcome-page__course-author">
                                    <img
                                        class="welcome-page__course-avatar"
                                        :src="course.instructorImg"
                                        alt=""
                                        width="32"
                                        height="32"
                                        loading="lazy"
                                    />
                                    <span class="welcome-page__course-author-name">By {{ course.instructor }}</span>
                                </div>
                                <div class="welcome-page__course-footer">
                                    <span class="welcome-page__course-price">{{ course.price }}</span>
                                    <div class="welcome-page__course-meta">
                                        <span class="material-symbols-outlined">schedule</span>
                                        {{ course.hours }}
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section class="welcome-page__section welcome-page__section--bg" aria-labelledby="why-heading">
                <div class="welcome-page__container welcome-page__why">
                    <div>
                        <span class="welcome-page__why-kicker">Expert Consulting</span>
                        <h2 id="why-heading" class="welcome-page__why-title">
                            Why Technical Teams <br />
                            Partner With Us
                        </h2>
                        <p class="welcome-page__why-lead">
                            Beyond education, we provide direct architectural oversight and strategic consulting to
                            ensure your systems remain resilient under any scale.
                        </p>
                        <ul class="welcome-page__why-list">
                            <li class="welcome-page__why-item">
                                <div class="welcome-page__why-icon">
                                    <span class="material-symbols-outlined">verified</span>
                                </div>
                                <div>
                                    <h4 class="welcome-page__why-item-title">ISO-Certified Workflows</h4>
                                    <p class="welcome-page__why-item-text">
                                        Rigorous architectural standards applied to every consulting project.
                                    </p>
                                </div>
                            </li>
                            <li class="welcome-page__why-item">
                                <div class="welcome-page__why-icon">
                                    <span class="material-symbols-outlined">psychology</span>
                                </div>
                                <div>
                                    <h4 class="welcome-page__why-item-title">Direct Access to Principals</h4>
                                    <p class="welcome-page__why-item-text">
                                        Work directly with former Fortune 500 Chief Architects and Staff Engineers.
                                    </p>
                                </div>
                            </li>
                            <li class="welcome-page__why-item">
                                <div class="welcome-page__why-icon">
                                    <span class="material-symbols-outlined">speed</span>
                                </div>
                                <div>
                                    <h4 class="welcome-page__why-item-title">High-Performance Delivery</h4>
                                    <p class="welcome-page__why-item-text">
                                        We don't just plan; we ship optimized, production-ready infrastructure.
                                    </p>
                                </div>
                            </li>
                        </ul>
                        <Link :href="route('services')" class="welcome-page__why-cta">Explore Consulting Services</Link>
                    </div>
                    <div class="welcome-page__why-grid">
                        <div class="welcome-page__why-col">
                            <div class="welcome-page__stat-card">
                                <div class="welcome-page__stat-card-val">95%</div>
                                <div class="welcome-page__stat-card-label">Student Satisfaction</div>
                            </div>
                            <img
                                class="welcome-page__why-photo"
                                :src="whyImages.a"
                                alt=""
                                width="400"
                                height="256"
                                loading="lazy"
                            />
                        </div>
                        <div class="welcome-page__why-col welcome-page__why-col--offset">
                            <img
                                class="welcome-page__why-photo"
                                :src="whyImages.b"
                                alt=""
                                width="400"
                                height="256"
                                loading="lazy"
                            />
                            <div class="welcome-page__stat-card welcome-page__stat-card--dark">
                                <div class="welcome-page__stat-card-val">150+</div>
                                <div class="welcome-page__stat-card-label">Partner Companies</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section
                id="instructors"
                class="welcome-page__section welcome-page__section--white"
                aria-labelledby="instructors-heading"
            >
                <div class="welcome-page__container">
                    <div class="welcome-page__section-head">
                        <h2 id="instructors-heading" class="welcome-page__h2">Learn From the Architects</h2>
                        <p class="welcome-page__section-desc">
                            Our instructors are active practitioners at the world's most innovative tech companies.
                        </p>
                    </div>
                    <div class="welcome-page__instructor-grid">
                        <div v-for="person in instructors" :key="person.name" class="welcome-page__instructor-card">
                            <div class="welcome-page__instructor-photo-wrap">
                                <img
                                    class="welcome-page__instructor-img"
                                    :src="person.image"
                                    alt=""
                                    width="400"
                                    height="500"
                                    loading="lazy"
                                />
                                <div class="welcome-page__instructor-overlay">
                                    <div class="welcome-page__instructor-social" aria-hidden="true">
                                        <span class="material-symbols-outlined">alternate_email</span>
                                        <span class="material-symbols-outlined">public</span>
                                    </div>
                                </div>
                            </div>
                            <h4 class="welcome-page__instructor-name">{{ person.name }}</h4>
                            <p class="welcome-page__instructor-role">{{ person.role }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="welcome-page__section welcome-page__section--bg" aria-labelledby="stories-heading">
                <div class="welcome-page__container">
                    <div class="welcome-page__testimonial-head">
                        <div>
                            <h2 id="stories-heading" class="welcome-page__h2">Student Success Stories</h2>
                            <p class="welcome-page__section-desc welcome-page__section-head--left">
                                Real engineers. Real career transformations.
                            </p>
                        </div>
                        <div class="welcome-page__testimonial-nav">
                            <button
                                type="button"
                                class="welcome-page__testimonial-nav-btn"
                                aria-label="Previous testimonials"
                                @click="scrollTestimonials(-1)"
                            >
                                <span class="material-symbols-outlined">chevron_left</span>
                            </button>
                            <button
                                type="button"
                                class="welcome-page__testimonial-nav-btn"
                                aria-label="Next testimonials"
                                @click="scrollTestimonials(1)"
                            >
                                <span class="material-symbols-outlined">chevron_right</span>
                            </button>
                        </div>
                    </div>
                    <div ref="testimonialTrack" class="welcome-page__testimonial-track">
                        <div v-for="t in testimonials" :key="t.name" class="welcome-page__testimonial-card">
                            <span class="material-symbols-outlined welcome-page__quote-icon" aria-hidden="true">
                                format_quote
                            </span>
                            <div class="welcome-page__stars">
                                <span v-for="i in 5" :key="i" class="material-symbols-outlined">star</span>
                            </div>
                            <p class="welcome-page__testimonial-text">"{{ t.quote }}"</p>
                            <div class="welcome-page__testimonial-author-row">
                                <div class="welcome-page__testimonial-avatar" aria-hidden="true" />
                                <div>
                                    <h5 class="welcome-page__testimonial-name">{{ t.name }}</h5>
                                    <p class="welcome-page__testimonial-role">{{ t.role }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="welcome-page__newsletter" aria-labelledby="newsletter-heading">
                <div class="welcome-page__newsletter-inner">
                    <div class="welcome-page__newsletter-glow" aria-hidden="true" />
                    <h2 id="newsletter-heading" class="welcome-page__newsletter-title">
                        Ready to accelerate your technical journey?
                    </h2>
                    <p class="welcome-page__newsletter-lead">
                        Join 25,000+ engineers receiving our weekly architectural deep-dives.
                    </p>
                    <form class="welcome-page__newsletter-form" @submit.prevent="submitNewsletter">
                        <div class="welcome-page__newsletter-input-wrap">
                            <input
                                v-model="newsletterEmail"
                                class="welcome-page__newsletter-input"
                                type="email"
                                name="email"
                                autocomplete="email"
                                placeholder="Enter your work email"
                                required
                            />
                            <button
                                class="welcome-page__newsletter-btn"
                                type="submit"
                                :disabled="newsletterBusy"
                            >
                                Subscribe
                            </button>
                        </div>
                    </form>
                    <p v-if="newsletterDone" class="welcome-page__newsletter-done">Thanks — you're on the list.</p>
                </div>
            </section>
        </main>

        <MarketingFooter />
    </div>
</template>

<style lang="scss" src="../../scss/welcome.scss"></style>
