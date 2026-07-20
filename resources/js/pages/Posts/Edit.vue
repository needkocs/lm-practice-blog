<script setup lang="ts">
import PostForm from '@/components/PostForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Post, SelectOption, Tag } from '@/types/blog';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{ post: Post; tags: Tag[]; visibilities: SelectOption[] }>();
</script>

<template>
    <AppLayout>
        <Head :title="`Редактирование: ${post.title}`" />
        <div class="mx-auto max-w-3xl rounded-md border border-slate-200 bg-white p-6">
            <h1 class="mb-6 text-3xl font-semibold">Редактирование поста</h1>
            <PostForm :action="`/posts/${props.post.slug}`" method="put" :post="post" :tags="tags" :visibilities="visibilities" />
        </div>
    </AppLayout>
</template>
