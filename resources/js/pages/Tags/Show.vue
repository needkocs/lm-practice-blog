<script setup lang="ts">
import PostList from '@/components/PostList.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Paginator, Post, Tag } from '@/types/blog';
import { Head } from '@inertiajs/vue3';

defineProps<{ tag: Tag; posts: Paginator<Post> }>();
</script>

<template>
    <AppLayout>
        <Head :title="tag.name" />
        <h1 class="mb-6 text-3xl font-semibold">Тег: {{ tag.name }}</h1>
        <PostList :posts="posts" />
    </AppLayout>
</template>
