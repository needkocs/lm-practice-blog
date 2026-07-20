<script setup lang="ts">
import PostForm from '@/components/PostForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { SelectOption, Tag } from '@/types/blog';
import { Head } from '@inertiajs/vue3';

defineProps<{ tags: Tag[]; visibilities: SelectOption[] }>();
</script>

<template>
    <AppLayout>
        <Head title="Создание поста" />
        <div class="mx-auto max-w-3xl rounded-md border border-slate-200 bg-white p-6">
            <h1 class="mb-6 text-3xl font-semibold">Создание поста</h1>
            <PostForm action="/posts" method="post" :tags="tags" :visibilities="visibilities" />
        </div>
    </AppLayout>
</template>
