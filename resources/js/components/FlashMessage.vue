<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

const page = usePage<{ flash?: { success?: string }; errors: Record<string, string> }>();
</script>

<template>
    <div class="mb-4 grid gap-2">
        <div v-if="page.props.flash?.success" class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ page.props.flash.success }}
        </div>
        <div v-for="(message, field) in page.props.errors" :key="field" class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ message }}
        </div>
    </div>
</template>
