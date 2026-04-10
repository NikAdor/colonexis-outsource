import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                /** Brand: primary #38bdf8, secondary #041228, accent #0092f6, bg #f1f1f1 */
                primary: 'var(--color-primary)',
                secondary: 'var(--color-secondary)',
                brandAccent: 'var(--color-brand-accent)',
                ink: {
                    DEFAULT: 'var(--color-ink)',
                    muted: 'var(--color-ink-muted)',
                },
                paper: 'var(--color-paper)',
                surface: {
                    DEFAULT: 'var(--color-surface)',
                    raised: 'var(--color-surface-raised)',
                },
                border: {
                    DEFAULT: 'var(--color-border)',
                    strong: 'var(--color-border-strong)',
                },
                accent: {
                    DEFAULT: 'var(--color-accent)',
                    hover: 'var(--color-accent-hover)',
                    fg: 'var(--color-accent-fg)',
                },
                hero: {
                    bg: 'var(--color-hero-bg)',
                    ink: 'var(--color-hero-ink)',
                    muted: 'var(--color-hero-muted)',
                },
                /** Editorial / course detail (Stitch-style tokens) */
                ed: {
                    surface: '#f9f9f9',
                    background: '#f9f9f9',
                    'on-surface': '#1a1c1c',
                    'on-surface-variant': '#46464e',
                    'on-background': '#1a1c1c',
                    'surface-low': '#f3f3f3',
                    'surface-container-low': '#f3f3f3',
                    'surface-container-high': '#e8e8e8',
                    'surface-container-lowest': '#ffffff',
                    'surface-container': '#eeeeee',
                    'tertiary-container': '#001e2c',
                    'on-tertiary-container': '#008ebf',
                    'secondary-fixed-dim': '#a7c8ff',
                    'on-primary-container': '#7d81a7',
                    outline: '#77767f',
                    'outline-variant': '#c7c5cf',
                    secondary: '#335f9b',
                    'tertiary-fixed': '#c4e7ff',
                    'on-tertiary-fixed': '#001e2c',
                    'tertiary-fixed-dim': '#7bd0ff',
                    primary: '#000000',
                    'primary-container': '#141939',
                    'secondary-container': '#94bdff',
                    'primary-fixed': '#dfe0ff',
                    'on-secondary-container': '#1b4b86',
                    'on-secondary-fixed-variant': '#144782',
                    'on-tertiary-fixed-variant': '#004c69',
                    'on-error-container': '#93000a',
                    error: '#ba1a1a',
                    'error-container': '#ffdad6',
                    'secondary-fixed': '#d5e3ff',
                    'on-secondary-fixed': '#001b3c',
                    'on-primary-fixed-variant': '#404467',
                    'on-primary': '#ffffff',
                    /** Admin student directory (curator shell) */
                    'curator-accent': '#0A0F2F',
                    'curator-shell': '#141939',
                },
            },
            fontFamily: {
                sans: ['IBM Plex Sans', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', 'Georgia', 'Times New Roman', 'serif'],
                manrope: ['Manrope', ...defaultTheme.fontFamily.sans],
                inter: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
