import './bootstrap'
import '../scss/app.scss'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import { i18nVue } from 'laravel-vue-i18n'
import device from 'vue3-device-detector'
import Toast from 'vue-toastification'

const toastOptions = {
    // You can set your default options here
}

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(device)
            .use(ZiggyVue, Ziggy)
            .use(Toast, toastOptions)
            .use(i18nVue, {
                resolve: async (lang) => {
                    const langs = import.meta.glob('../../lang/*.json')
                    return await langs[`../../lang/${lang}.json`]()
                },
            })
            .mount(el)
    },
    progress: {
        color: '#4B5563',
    },
})
