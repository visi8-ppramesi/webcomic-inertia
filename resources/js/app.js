require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
// import store from './Store/store'
import mitt from 'mitt'
import { Vue3Mq, MqResponsive } from "vue3-mq"
import Swal from "sweetalert2";
import helper from './helpers'
import DRM from './Utils/DRM'
// import "../css/swiper.min.css"
// import "../css/swiper-bundle.min.css"

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
const emitter = mitt()
const p = createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        window.l = createApp({ render: () => h(app, props) })
        window.l.config.globalProperties.emitter = emitter
        window.l.config.globalProperties.$Swal = Swal
        window.l.provide('swal', Swal)
        window.l.provide('helpers', helper)
        window.l.provide('DRM', DRM)
        // window.l.use(store)
        window.l.use(Vue3Mq, {
            preset: "tailwind"
        })
        window.l.component('Link', Link)
        window.l.component('mq-responsive', MqResponsive)
        return l
            .use(plugin)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
