<script setup lang="ts">
import type { Tag } from '@/types/blog';
import { computed, ref } from 'vue';

const props = defineProps<{ modelValue: string[]; tags: Tag[] }>();
const emit = defineEmits<{ 'update:modelValue': [value: string[]] }>();
const draft = ref('');

const existingNames = computed(() => props.tags.map((tag) => tag.name));

function addTag(name: string) {
    const normalized = name.trim();
    if (!normalized || props.modelValue.map((tag) => tag.toLowerCase()).includes(normalized.toLowerCase())) {
        return;
    }
    emit('update:modelValue', [...props.modelValue, normalized]);
    draft.value = '';
}

function removeTag(name: string) {
    emit('update:modelValue', props.modelValue.filter((tag) => tag !== name));
}
</script>

<template>
    <div class="grid gap-2">
        <div class="flex flex-wrap gap-2">
            <button
                v-for="name in existingNames"
                :key="name"
                type="button"
                class="rounded-md border border-slate-200 px-2 py-1 text-xs hover:bg-slate-100"
                @click="addTag(name)"
            >
                {{ name }}
            </button>
        </div>
        <div class="flex gap-2">
            <input v-model="draft" type="text" class="min-w-0 flex-1 rounded-md border border-slate-300 bg-white px-3 py-2" placeholder="Новый тег" @keydown.enter.prevent="addTag(draft)" />
            <button type="button" class="rounded-md bg-slate-900 px-3 py-2 text-sm text-white" @click="addTag(draft)">Добавить</button>
        </div>
        <div class="flex flex-wrap gap-2">
            <button v-for="tag in modelValue" :key="tag" type="button" class="rounded-md bg-blue-100 px-2 py-1 text-xs text-blue-800" @click="removeTag(tag)">
                {{ tag }} x
            </button>
        </div>
    </div>
</template>
