<script setup lang="ts">
const props = defineProps<{ label: string; message: string }>();
const emit = defineEmits<{ confirm: [] }>();

function confirmAction() {
    if (window.confirm(props.message)) {
        emit('confirm');
    }
}
</script>

<template>
    <button type="button" class="rounded-md bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700" @click="confirmAction">
        {{ label }}
    </button>
</template>
