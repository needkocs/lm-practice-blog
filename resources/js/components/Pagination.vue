<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { PaginationLink } from '@/types/blog';

defineProps<{ links: PaginationLink[] }>();
</script>

<template>
    <nav v-if="links.length > 3" class="mt-6 flex flex-wrap gap-2">
        <component
            :is="link.url ? Link : 'span'"
            v-for="link in links"
            :key="link.label"
            :href="link.url || undefined"
            preserve-scroll
            class="rounded-md border px-3 py-2 text-sm"
            :class="link.active ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-200 bg-white text-slate-700'"
            v-html="link.label"
        />
    </nav>
</template>
