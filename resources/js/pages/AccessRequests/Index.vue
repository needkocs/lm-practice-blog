<script setup lang="ts">
import AccessRequestCard from '@/components/AccessRequestCard.vue';
import EmptyState from '@/components/EmptyState.vue';
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Paginator } from '@/types/blog';
import { Head } from '@inertiajs/vue3';

interface AccessRequest {
    id: number;
    message: string | null;
    status: string;
    created_at: string;
    user: { id: number; name: string; email: string };
    post: { id: number; title: string; slug: string };
}

defineProps<{ requests: Paginator<AccessRequest> }>();
</script>

<template>
    <AppLayout>
        <Head title="Запросы доступа" />
        <h1 class="mb-6 text-3xl font-semibold">Запросы доступа</h1>
        <div v-if="requests.data.length" class="grid gap-4">
            <AccessRequestCard v-for="request in requests.data" :key="request.id" :request="request" />
        </div>
        <EmptyState v-else title="Входящих запросов нет" />
        <Pagination :links="requests.links" />
    </AppLayout>
</template>
