<script setup lang="ts">
import PostList from '@/components/PostList.vue';
import TagFilter from '@/components/TagFilter.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Paginator, Post, SelectOption, Tag } from '@/types/blog';
import { Head } from '@inertiajs/vue3';

defineProps<{ posts: Paginator<Post>; tags: Tag[]; filters: Record<string, string | undefined>; sorts: SelectOption[]; visibilities: SelectOption[] }>();
</script>

<template>
    <AppLayout>
        <Head title="Мои посты" />
        <h1 class="mb-6 text-3xl font-semibold">Мои посты</h1>
        <TagFilter :tags="tags" :filters="filters" :sorts="sorts" :visibilities="visibilities" show-visibility />
        <PostList :posts="posts" />
    </AppLayout>
</template>
