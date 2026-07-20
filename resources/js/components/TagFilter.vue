<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import type { SelectOption, Tag } from '@/types/blog';
import { computed, reactive, ref, watch } from 'vue';

const props = defineProps<{
    tags: Tag[];
    filters: Record<string, string | undefined>;
    sorts?: SelectOption[];
    showVisibility?: boolean;
    visibilities?: SelectOption[];
}>();

const isResetting = ref(false);

const state = reactive({
    search: props.filters.search ?? '',
    tags: props.filters.tags ?? '',
    sort: props.filters.sort ?? 'newest',
    visibility: props.filters.visibility ?? '',
});

const sortOptions = computed<SelectOption[]>(() => props.sorts ?? [
    { value: 'newest', label: 'Сначала новые' },
    { value: 'oldest', label: 'Сначала старые' },
    { value: 'title_asc', label: 'Заголовок А-Я' },
    { value: 'title_desc', label: 'Заголовок Я-А' },
]);

const hasActiveFilters = computed(() => {
    return state.search !== '' || state.tags !== '' || state.visibility !== '' || state.sort !== 'newest';
});

function apply(): void {
    router.get(
        window.location.pathname,
        {
            search: state.search || undefined,
            tags: state.tags || undefined,
            sort: state.sort !== 'newest' ? state.sort : undefined,
            visibility: state.visibility || undefined,
        },
        { preserveState: true, preserveScroll: true },
    );
}

let filterTimeout: ReturnType<typeof setTimeout> | null = null;

function clearPendingApply(): void {
    if (filterTimeout) {
        clearTimeout(filterTimeout);
        filterTimeout = null;
    }
}

function resetFilters(): void {
    isResetting.value = true;
    clearPendingApply();

    state.search = '';
    state.tags = '';
    state.sort = 'newest';
    state.visibility = '';

    router.get(window.location.pathname, {}, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            isResetting.value = false;
        },
    });
}

watch(
    state,
    () => {
        if (isResetting.value) {
            return;
        }

        clearPendingApply();

        filterTimeout = setTimeout(() => {
            apply();
        }, 350);
    },
    { deep: true },
);
</script>

<template>
    <form class="mb-6 grid gap-3 rounded-md border border-slate-200 bg-white p-4 md:grid-cols-5" @submit.prevent="apply">
        <input v-model="state.search" type="search" placeholder="Поиск по заголовку" class="rounded-md border border-slate-300 bg-white px-3 py-2" />
        <select v-model="state.tags" class="rounded-md border border-slate-300 bg-white px-3 py-2">
            <option value="">Все теги</option>
            <option v-for="tag in tags" :key="tag.id" :value="tag.slug">{{ tag.name }}</option>
        </select>
        <select v-if="showVisibility" v-model="state.visibility" class="rounded-md border border-slate-300 bg-white px-3 py-2">
            <option value="">Любая видимость</option>
            <option v-for="visibility in visibilities" :key="visibility.value" :value="visibility.value">{{ visibility.label }}</option>
        </select>
        <select v-model="state.sort" class="rounded-md border border-slate-300 bg-white px-3 py-2">
            <option v-for="sort in sortOptions" :key="sort.value" :value="sort.value">{{ sort.label }}</option>
        </select>
        <button type="button" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 disabled:opacity-50" :disabled="!hasActiveFilters" @click="resetFilters">
            Сбросить
        </button>
    </form>
</template>
