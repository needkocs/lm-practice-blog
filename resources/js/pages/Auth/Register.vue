<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({ name: '', email: '', password: '', password_confirmation: '' });
function submit() {
    form.post('/register', { onFinish: () => form.reset('password', 'password_confirmation') });
}
</script>

<template>
    <AppLayout>
        <Head title="Регистрация" />
        <form class="mx-auto grid max-w-md gap-4 rounded-md border border-slate-200 bg-white p-6" @submit.prevent="submit">
            <h1 class="text-2xl font-semibold">Регистрация</h1>
            <label class="grid gap-1"><span class="text-sm font-medium">Имя</span><input v-model="form.name" class="rounded-md border border-slate-300 px-3 py-2" /><span v-if="form.errors.name" class="text-sm text-red-600">{{ form.errors.name }}</span></label>
            <label class="grid gap-1"><span class="text-sm font-medium">Email</span><input v-model="form.email" type="email" class="rounded-md border border-slate-300 px-3 py-2" /><span v-if="form.errors.email" class="text-sm text-red-600">{{ form.errors.email }}</span></label>
            <label class="grid gap-1"><span class="text-sm font-medium">Пароль</span><input v-model="form.password" type="password" class="rounded-md border border-slate-300 px-3 py-2" /><span v-if="form.errors.password" class="text-sm text-red-600">{{ form.errors.password }}</span></label>
            <label class="grid gap-1"><span class="text-sm font-medium">Подтверждение пароля</span><input v-model="form.password_confirmation" type="password" class="rounded-md border border-slate-300 px-3 py-2" /></label>
            <button type="submit" :disabled="form.processing" class="rounded-md bg-blue-600 px-4 py-2 font-medium text-white disabled:opacity-60">Зарегистрироваться</button>
            <Link href="/login" class="text-sm text-blue-600">Уже есть аккаунт?</Link>
        </form>
    </AppLayout>
</template>
