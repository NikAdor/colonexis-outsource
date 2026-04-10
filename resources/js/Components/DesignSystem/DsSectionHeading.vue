<script setup>
defineProps({
    eyebrow: {
        type: String,
        default: '',
    },
    title: {
        type: String,
        required: true,
    },
    /** For aria-labelledby on parent sections */
    titleId: {
        type: String,
        default: '',
    },
    align: {
        type: String,
        default: 'left',
        validator: (v) => ['left', 'center'].includes(v),
    },
});
</script>

<template>
    <header :class="align === 'center' ? 'text-center' : 'text-left'">
        <p v-if="eyebrow" class="ds-eyebrow">{{ eyebrow }}</p>
        <h2
            :id="titleId || undefined"
            class="mt-3 font-display text-3xl font-semibold tracking-tight text-ink md:text-4xl lg:text-[2.75rem] lg:leading-tight"
        >
            {{ title }}
        </h2>
        <div v-if="$slots.default" class="mt-4 max-w-2xl text-base leading-relaxed text-ink-muted" :class="align === 'center' ? 'mx-auto' : ''">
            <slot />
        </div>
        <div v-if="align === 'left'" class="ds-rule mt-8 max-w-md" aria-hidden="true" />
    </header>
</template>
