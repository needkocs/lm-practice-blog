<script setup lang="ts">
defineProps<{ visibility: string; label: string }>();
</script>

<template>
    <span
        class="inline-flex rounded-md px-2 py-1 text-xs font-medium"
        :class="visibility === 'public' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'"
    >
        {{ label }}
    </span>
</template>
