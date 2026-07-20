<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface AccessRequest {
    id: number;
    message: string | null;
    status: string;
    created_at: string;
    user: { id: number; name: string; email: string };
    post: { id: number; title: string; slug: string };
}

const props = defineProps<{ request: AccessRequest }>();
const form = useForm({ status: props.request.status });
const statusLabel = computed(() => {
    return {
        pending: 'Ожидает',
        approved: 'Одобрен',
        rejected: 'Отклонен',
    }[props.request.status] ?? props.request.status;
});

function update(status: string) {
    form.status = status;
    form.patch(`/access-requests/${props.request.id}`, { preserveScroll: true });
}
</script>

<template>
    <article class="rounded-md border border-slate-200 bg-white p-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold">{{ request.post.title }}</h2>
                <p class="text-sm text-slate-600">{{ request.user.name }} · {{ request.user.email }}</p>
            </div>
            <span class="rounded-md bg-slate-100 px-2 py-1 text-xs">{{ statusLabel }}</span>
        </div>
        <p v-if="request.message" class="mt-3 text-sm text-slate-700">{{ request.message }}</p>
        <div class="mt-4 flex gap-2">
            <button type="button" class="rounded-md bg-emerald-600 px-3 py-2 text-sm text-white" @click="update('approved')">Одобрить</button>
            <button type="button" class="rounded-md bg-red-600 px-3 py-2 text-sm text-white" @click="update('rejected')">Отклонить</button>
            <button type="button" class="rounded-md bg-slate-700 px-3 py-2 text-sm text-white" @click="update('pending')">Ожидает</button>
        </div>
    </article>
</template>
