import '../css/app.css';
import '../scss/admin-shell.scss';
import '../scss/admin-analytics.scss';
import '../scss/student-shell.scss';
import '../scss/student-dashboard.scss';
import '../scss/student-learning.scss';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { MotionPlugin } from '@vueuse/motion';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(MotionPlugin)
            .mount(el);
    },
    progress: {
        color: '#38bdf8',
    },
});
