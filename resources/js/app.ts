import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, createSSRApp, h } from 'vue';
import type { DefineComponent } from 'vue';

const appName = import.meta.env.VITE_APP_NAME || 'Блог';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => {
        const pages = import.meta.glob('./pages/**/*.vue', { eager: true });
        return pages[`./pages/${name}.vue`] as DefineComponent;
    },
    setup({ el, App, props, plugin }) {
        const vueApp = (import.meta.env.SSR ? createSSRApp : createApp)({
            render: () => h(App, props),
        }).use(plugin);

        if (import.meta.env.SSR) {
            return vueApp;
        }

        vueApp.mount(el);
    },
    progress: {
        color: '#2563eb',
    },
});
