<script setup lang="ts">
import TagSelector from '@/components/TagSelector.vue';
import { useForm } from '@inertiajs/vue3';
import type { Post, SelectOption, Tag } from '@/types/blog';

const props = defineProps<{
    action: string;
    method: 'post' | 'put';
    tags: Tag[];
    visibilities: SelectOption[];
    post?: Post;
}>();

const form = useForm({
    title: props.post?.title ?? '',
    content: props.post?.content ?? '',
    visibility: props.post?.visibility ?? 'public',
    tags: props.post?.tags.map((tag) => tag.name) ?? [],
});

function submit() {
    form.submit(props.method, props.action, { preserveScroll: true });
}
</script>

<template>
    <form class="grid gap-5" @submit.prevent="submit">
        <label class="grid gap-1">
            <span class="text-sm font-medium">Заголовок</span>
            <input v-model="form.title" type="text" class="rounded-md border border-slate-300 bg-white px-3 py-2" />
            <span v-if="form.errors.title" class="text-sm text-red-600">{{ form.errors.title }}</span>
        </label>
        <label class="grid gap-1">
            <span class="text-sm font-medium">Содержимое</span>
            <textarea v-model="form.content" rows="12" class="rounded-md border border-slate-300 bg-white px-3 py-2" />
            <span v-if="form.errors.content" class="text-sm text-red-600">{{ form.errors.content }}</span>
        </label>
        <label class="grid gap-1">
            <span class="text-sm font-medium">Видимость</span>
            <select v-model="form.visibility" class="rounded-md border border-slate-300 bg-white px-3 py-2">
                <option v-for="visibility in visibilities" :key="visibility.value" :value="visibility.value">{{ visibility.label }}</option>
            </select>
        </label>
        <div class="grid gap-1">
            <span class="text-sm font-medium">Теги</span>
            <TagSelector v-model="form.tags" :tags="tags" />
            <span v-if="form.errors.tags" class="text-sm text-red-600">{{ form.errors.tags }}</span>
        </div>
        <button type="submit" :disabled="form.processing" class="rounded-md bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700 disabled:opacity-60">
            {{ form.processing ? 'Сохранение...' : 'Сохранить пост' }}
        </button>
    </form>
</template>
