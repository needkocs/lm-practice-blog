<script setup lang="ts">
import EmptyState from '@/components/EmptyState.vue';
import Pagination from '@/components/Pagination.vue';
import PostCard from '@/components/PostCard.vue';
import type { Paginator, Post } from '@/types/blog';

defineProps<{ posts: Paginator<Post> }>();
</script>

<template>
    <div>
        <div v-if="posts.data.length" class="grid gap-4 md:grid-cols-2">
            <PostCard v-for="post in posts.data" :key="post.id" :post="post" />
        </div>
        <EmptyState v-else title="Посты не найдены" description="Попробуйте изменить фильтры или создайте первый пост." />
        <Pagination :links="posts.links" />
    </div>
</template>
