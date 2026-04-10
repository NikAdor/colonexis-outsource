<script setup>
import { Head } from '@inertiajs/vue3';
import MarketingFooter from '@/Components/MarketingFooter.vue';
import MarketingHeader from '@/Components/MarketingHeader.vue';

defineProps({
    canLogin: { type: Boolean, default: true },
    canRegister: { type: Boolean, default: true },
});
</script>

<template>
    <div class="marketing-layout">
        <Head>
            <link rel="preconnect" href="https://fonts.googleapis.com" />
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="" />
            <link
                href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap"
                rel="stylesheet"
            />
            <link
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap"
                rel="stylesheet"
            />
        </Head>
        <MarketingHeader :can-login="canLogin" :can-register="canRegister" />
        <div class="marketing-layout__content">
            <slot />
        </div>
        <MarketingFooter />
    </div>
</template>

<style lang="scss" src="../../scss/marketing-chrome.scss"></style>
