<script setup>
defineProps({
    href: {
        type: String,
        default: '#main-content',
    },
});
</script>

<template>
    <a :href="href" class="ds-skip">Skip to main content</a>
</template>
