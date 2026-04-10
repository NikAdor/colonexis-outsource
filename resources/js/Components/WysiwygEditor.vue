<script setup>
import { onBeforeUnmount, watch } from 'vue';
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Placeholder from '@tiptap/extension-placeholder';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: 'Write the full description…',
    },
});

const emit = defineEmits(['update:modelValue']);

const editor = useEditor({
    content: props.modelValue || '',
    extensions: [
        StarterKit,
        Placeholder.configure({ placeholder: props.placeholder }),
    ],
    editorProps: {
        attributes: {
            class:
                'min-h-[12rem] max-w-none px-3 py-2 text-sm text-ink focus:outline-none',
        },
    },
    immediatelyRender: false,
    onUpdate: ({ editor: ed }) => {
        emit('update:modelValue', ed.getHTML());
    },
});

watch(
    () => props.modelValue,
    (v) => {
        const ed = editor.value;
        if (!ed || ed.isDestroyed) {
            return;
        }
        const next = v || '';
        const current = ed.getHTML();
        if (next === current) {
            return;
        }
        ed.commands.setContent(next, false);
    },
);

onBeforeUnmount(() => {
    editor.value?.destroy();
});

function toggleBold() {
    editor.value?.chain().focus().toggleBold().run();
}
function toggleItalic() {
    editor.value?.chain().focus().toggleItalic().run();
}
function toggleBulletList() {
    editor.value?.chain().focus().toggleBulletList().run();
}
function toggleOrderedList() {
    editor.value?.chain().focus().toggleOrderedList().run();
}
</script>

<template>
    <div class="overflow-hidden rounded-md border border-gray-300 bg-white shadow-sm focus-within:border-accent focus-within:ring-1 focus-within:ring-accent">
        <div
            v-if="editor"
            class="flex flex-wrap gap-1 border-b border-border bg-surface-raised px-2 py-1.5"
        >
            <button
                type="button"
                class="rounded px-2 py-1 text-xs font-semibold text-ink hover:bg-border"
                :class="{ 'bg-border': editor.isActive('bold') }"
                @click="toggleBold"
            >
                B
            </button>
            <button
                type="button"
                class="rounded px-2 py-1 text-xs italic text-ink hover:bg-border"
                :class="{ 'bg-border': editor.isActive('italic') }"
                @click="toggleItalic"
            >
                I
            </button>
            <button
                type="button"
                class="rounded px-2 py-1 text-xs text-ink hover:bg-border"
                :class="{ 'bg-border': editor.isActive('bulletList') }"
                @click="toggleBulletList"
            >
                • List
            </button>
            <button
                type="button"
                class="rounded px-2 py-1 text-xs text-ink hover:bg-border"
                :class="{ 'bg-border': editor.isActive('orderedList') }"
                @click="toggleOrderedList"
            >
                1. List
            </button>
        </div>
        <EditorContent v-if="editor" :editor="editor" class="wysiwyg-editor" />
    </div>
</template>

<style scoped>
.wysiwyg-editor :deep(.ProseMirror) {
    min-height: 12rem;
    outline: none;
}
.wysiwyg-editor :deep(.ProseMirror p.is-editor-empty:first-child::before) {
    color: var(--color-ink-muted);
    content: attr(data-placeholder);
    float: left;
    height: 0;
    pointer-events: none;
}
</style>
