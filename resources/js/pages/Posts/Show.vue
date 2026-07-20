<script setup lang="ts">
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import PostAccessPanel from '@/components/PostAccessPanel.vue';
import PostVisibilityBadge from '@/components/PostVisibilityBadge.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Post } from '@/types/blog';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps<{ post: Post; accessStatus: string | null }>();

function destroyPost() {
    router.delete(`/posts/${props.post.slug}`);
}
</script>

<template>
    <AppLayout>
        <Head :title="post.title" />
        <article class="mx-auto max-w-3xl rounded-md border border-slate-200 bg-white p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <PostVisibilityBadge :visibility="post.visibility" :label="post.visibility_label" />
                    <span class="text-sm text-slate-500">{{ post.published_at }}</span>
                </div>
                <div v-if="post.can.update && post.slug" class="flex gap-2">
                    <Link :href="`/posts/${post.slug}/edit`" class="rounded-md bg-slate-900 px-3 py-2 text-sm text-white">Редактировать</Link>
                    <ConfirmDialog label="Удалить" message="Удалить этот пост?" @confirm="destroyPost" />
                </div>
            </div>
            <h1 class="mt-5 text-3xl font-semibold">{{ post.title }}</h1>
            <p class="mt-2 text-sm text-slate-600">автор: <Link :href="`/users/${post.author.id}`" class="font-medium text-blue-600">{{ post.author.name }}</Link></p>
            <div class="mt-4 flex flex-wrap gap-2">
                <Link v-for="tag in post.tags" :key="tag.id" :href="`/posts?tags=${tag.slug}`" class="rounded-md bg-slate-100 px-2 py-1 text-xs">{{ tag.name }}</Link>
            </div>
            <PostAccessPanel class="mt-6" :post="post" :access-status="accessStatus" />
            <div v-if="post.content" class="mt-6 whitespace-pre-wrap text-base leading-8 text-slate-800">{{ post.content }}</div>
        </article>
    </AppLayout>
</template>
