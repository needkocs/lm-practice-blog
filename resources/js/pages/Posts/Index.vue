<script setup lang="ts">
import PostList from '@/components/PostList.vue';
import TagFilter from '@/components/TagFilter.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Paginator, Post, SelectOption, Tag } from '@/types/blog';
import { Head } from '@inertiajs/vue3';

defineProps<{ posts: Paginator<Post>; tags: Tag[]; filters: Record<string, string | undefined>; sorts: SelectOption[] }>();
</script>

<template>
    <AppLayout>
        <Head title="Посты" />
        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold">Посты</h1>
            </div>
        </div>
        <TagFilter :tags="tags" :filters="filters" :sorts="sorts" />
        <PostList :posts="posts" />
    </AppLayout>
</template>
