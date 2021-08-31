require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import store from './Store/store'
import mitt from 'mitt'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
const emitter = mitt()
const p = createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        window.l = createApp({ render: () => h(app, props) })
        window.l.config.globalProperties.emitter = emitter
        window.l.use(store)
        return l
            .use(plugin)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
