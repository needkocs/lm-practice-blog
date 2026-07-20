<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import type { Post, User } from '@/types/blog';
import { computed } from 'vue';

const props = defineProps<{ post: Post; accessStatus: string | null }>();
const page = usePage<{ auth: { user: User | null } }>();
const form = useForm({ message: '' });
const statusLabel = computed(() => {
    return {
        pending: 'Ожидает',
        approved: 'Одобрен',
        rejected: 'Отклонен',
    }[props.accessStatus ?? ''] ?? props.accessStatus;
});

function submit() {
    form.post(`/posts/${props.post.slug}/access-requests`, { preserveScroll: true });
}
</script>

<template>
    <div v-if="!post.can.view_full" class="rounded-md border border-amber-200 bg-amber-50 p-4 text-amber-950">
        <p class="font-medium">Этот пост доступен по запросу.</p>
        <p class="mt-1 text-sm">Полный текст видят только автор и одобренные читатели.</p>
        <form v-if="page.props.auth.user && !accessStatus" class="mt-4 grid gap-2" @submit.prevent="submit">
            <textarea v-model="form.message" rows="3" class="rounded-md border border-amber-300 bg-white px-3 py-2" placeholder="Сообщение автору, необязательно" />
            <button type="submit" :disabled="form.processing" class="rounded-md bg-amber-700 px-3 py-2 text-sm font-medium text-white disabled:opacity-60">
                {{ form.processing ? 'Отправка...' : 'Запросить доступ' }}
            </button>
        </form>
        <p v-else-if="accessStatus" class="mt-3 text-sm">Статус запроса: {{ statusLabel }}</p>
    </div>
</template>
