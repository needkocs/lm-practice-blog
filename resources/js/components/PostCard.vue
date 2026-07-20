<script setup lang="ts">
import PostVisibilityBadge from '@/components/PostVisibilityBadge.vue';
import { Link } from '@inertiajs/vue3';
import type { Post } from '@/types/blog';

defineProps<{ post: Post }>();
</script>

<template>
    <article class="rounded-md border border-slate-200 bg-white p-5">
        <div class="flex flex-wrap items-center gap-2">
            <PostVisibilityBadge :visibility="post.visibility" :label="post.visibility_label" />
            <span class="text-xs text-slate-500">{{ post.published_at }}</span>
        </div>
        <h2 class="mt-3 text-xl font-semibold">
            <Link :href="`/posts/${post.slug}`" class="hover:text-blue-600">{{ post.title }}</Link>
        </h2>
        <p class="mt-2 text-sm text-slate-600">
            автор: <Link :href="`/users/${post.author.id}`" class="font-medium hover:text-blue-600">{{ post.author.name }}</Link>
        </p>
        <p class="mt-3 text-sm leading-6 text-slate-700">{{ post.excerpt }}</p>
        <div class="mt-4 flex flex-wrap gap-2">
            <Link v-for="tag in post.tags" :key="tag.id" :href="`/posts?tags=${tag.slug}`" class="rounded-md bg-slate-100 px-2 py-1 text-xs text-slate-700 hover:bg-slate-200">
                {{ tag.name }}
            </Link>
        </div>
    </article>
</template>
