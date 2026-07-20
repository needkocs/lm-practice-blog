<script setup lang="ts">
import FlashMessage from '@/components/FlashMessage.vue';
import { Link, usePage } from '@inertiajs/vue3';
import type { User } from '@/types/blog';

const page = usePage<{
    auth: { user: User | null };
    name: string;
}>();
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-950">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-3 px-4 py-4">
                <Link href="/posts" class="text-lg font-semibold">Блог</Link>
                <nav class="flex flex-wrap items-center gap-2 text-sm">
                    <Link href="/posts" class="rounded-md px-3 py-2 hover:bg-slate-100">Посты</Link>
                    <Link v-if="page.props.auth.user" href="/feed" class="rounded-md px-3 py-2 hover:bg-slate-100">Лента</Link>
                    <Link v-if="page.props.auth.user" href="/my-posts" class="rounded-md px-3 py-2 hover:bg-slate-100">Мои посты</Link>
                    <Link v-if="page.props.auth.user" href="/access-requests" class="rounded-md px-3 py-2 hover:bg-slate-100">Запросы</Link>
                    <Link v-if="page.props.auth.user" href="/posts/create" class="rounded-md bg-blue-600 px-3 py-2 font-medium text-white hover:bg-blue-700">Новый пост</Link>
                    <Link v-if="!page.props.auth.user" href="/login" class="rounded-md px-3 py-2 hover:bg-slate-100">Вход</Link>
                    <Link v-if="!page.props.auth.user" href="/register" class="rounded-md bg-blue-600 px-3 py-2 font-medium text-white hover:bg-blue-700">Регистрация</Link>
                    <Link v-if="page.props.auth.user" href="/logout" method="post" as="button" class="rounded-md px-3 py-2 hover:bg-slate-100">Выход</Link>
                </nav>
            </div>
        </header>
        <main class="mx-auto max-w-6xl px-4 py-6">
            <FlashMessage />
            <slot />
        </main>
    </div>
</template>
