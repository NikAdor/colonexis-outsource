<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    /** When set, wraps the logo in an Inertia `Link`. */
    to: {
        type: String,
        default: null,
    },
    imgClass: {
        type: String,
        default: 'h-8 w-auto max-w-[220px] object-contain object-left',
    },
    /** Merged onto the root link/span (e.g. marketing layout hooks). */
    wrapperClass: {
        type: String,
        default: '',
    },
});

const rootClass =
    'inline-flex shrink-0 items-center rounded-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400/60';
</script>

<template>
    <Link v-if="to" :href="to" :class="[rootClass, wrapperClass]">
        <img src="/img/Colonexis-logo.png" alt="Colonexis" :class="imgClass" />
    </Link>
    <span v-else :class="[rootClass, wrapperClass]">
        <img src="/img/Colonexis-logo.png" alt="Colonexis" :class="imgClass" />
    </span>
</template>
