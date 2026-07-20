<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import type { User } from '@/types/blog';
import { computed } from 'vue';

const props = defineProps<{ user: User; isFollowing: boolean }>();
const page = usePage<{ auth: { user: User | null } }>();
const isOwnProfile = computed(() => page.props.auth.user?.id === props.user.id);

function toggle() {
    if (props.isFollowing) {
        router.delete(`/users/${props.user.id}/follow`, { preserveScroll: true });
        return;
    }

    router.post(`/users/${props.user.id}/follow`, {}, { preserveScroll: true });
}
</script>

<template>
    <button v-if="page.props.auth.user && !isOwnProfile" type="button" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700" @click="toggle">
        {{ isFollowing ? 'Отписаться' : 'Подписаться' }}
    </button>
</template>
