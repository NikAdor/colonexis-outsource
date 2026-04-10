import { computed, onUnmounted, ref, watch } from 'vue';

export function formatLongDate(ymd) {
    if (!ymd) {
        return '';
    }
    const d = new Date(`${ymd}T12:00:00`);
    if (Number.isNaN(d.getTime())) {
        return ymd;
    }
    return d.toLocaleDateString(undefined, {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    });
}

/** Date/time line for the live preview card (matches form fields). */
export function buildDateSummaryFromForm(form) {
    if (form.all_day && form.start_date) {
        const end = form.end_date || form.start_date;
        const a = formatLongDate(form.start_date);
        const b = formatLongDate(end);
        if (form.start_date === end) {
            return `All day · ${a}`;
        }
        return `All day · ${a} – ${b}`;
    }
    if (form.starts_at) {
        const start = form.starts_at.replace('T', ' ');
        const end = form.ends_at ? ` → ${form.ends_at.replace('T', ' ')}` : '';
        return `${start}${end}`;
    }
    return '—';
}

export function buildRecurrenceSummaryFromForm(form) {
    const freq = form.recurrence_frequency;
    if (freq === 'once' && form.recurrence_on) {
        return `One multi-day event starting on ${formatLongDate(form.recurrence_on)}`;
    }
    if (freq === 'none') {
        return 'Does not repeat.';
    }
    return `Repeats ${freq}.`;
}

export function buildExclusionsSummaryFromForm(exclusionDates) {
    const filtered = (exclusionDates || []).filter((d) => d !== null && d !== '');
    if (!filtered.length) {
        return '';
    }
    return filtered.map((d) => formatLongDate(d)).join(', ');
}

/**
 * Resolves featured image URL for preview: new file (object URL), existing, or none.
 */
export function useFeaturedPreview(form, getExistingUrl) {
    const objectUrl = ref(null);

    function revoke() {
        if (objectUrl.value) {
            URL.revokeObjectURL(objectUrl.value);
            objectUrl.value = null;
        }
    }

    function refresh() {
        revoke();
        if (form.featured_image instanceof File) {
            objectUrl.value = URL.createObjectURL(form.featured_image);
        }
    }

    watch(
        () => [form.featured_image, form.remove_featured_image],
        refresh,
        { immediate: true },
    );

    onUnmounted(revoke);

    const displaySrc = computed(() => {
        if (form.featured_image instanceof File) {
            return objectUrl.value;
        }
        if (form.remove_featured_image) {
            return null;
        }
        const existing = getExistingUrl();
        return existing || null;
    });

    return { displaySrc };
}
