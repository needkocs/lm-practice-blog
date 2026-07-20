<script setup lang="ts">
import FollowButton from '@/components/FollowButton.vue';
import PostList from '@/components/PostList.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Paginator, Post, User } from '@/types/blog';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{ profile: User; posts: Paginator<Post>; isFollowing: boolean }>();
</script>

<template>
    <AppLayout>
        <Head :title="profile.name" />
        <section class="mb-6 rounded-md border border-slate-200 bg-white p-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-semibold">{{ profile.name }}</h1>
                    <p class="mt-1 text-sm text-slate-600">Дата регистрации: {{ profile.created_at }}</p>
                </div>
                <FollowButton :user="profile" :is-following="isFollowing" />
            </div>
            <div class="mt-5 flex gap-4 text-sm">
                <Link :href="`/users/${profile.id}/followers`">Подписчики: {{ profile.followers_count }}</Link>
                <Link :href="`/users/${profile.id}/following`">Подписки: {{ profile.following_count }}</Link>
            </div>
        </section>
        <PostList :posts="posts" />
    </AppLayout>
</template>
