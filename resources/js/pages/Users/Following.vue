<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Paginator, User } from '@/types/blog';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{ profile: User; users: Paginator<User> }>();
</script>

<template>
    <AppLayout>
        <Head :title="`Подписки: ${profile.name}`" />
        <h1 class="mb-6 text-3xl font-semibold">Подписки</h1>
        <div class="grid gap-3">
            <Link v-for="user in users.data" :key="user.id" :href="`/users/${user.id}`" class="rounded-md border border-slate-200 bg-white p-4">{{ user.name }}</Link>
        </div>
        <Pagination :links="users.links" />
    </AppLayout>
</template>
