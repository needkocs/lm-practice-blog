<script setup lang="ts">
defineProps<{ title: string; description?: string }>();
</script>

<template>
    <div class="rounded-md border border-dashed border-slate-300 bg-white p-8 text-center">
        <h2 class="text-base font-semibold">{{ title }}</h2>
        <p v-if="description" class="mt-2 text-sm text-slate-600">{{ description }}</p>
    </div>
</template>
