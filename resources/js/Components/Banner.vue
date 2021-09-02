<template>
    <vueper-slides slide-image-inside class="w-full">
        <vueper-slide v-for="(banner, idx) in banners" :key="idx" :title="banner.title" :image="banner.image" :link="route(banner.route.name, banner.route.params)">
            <!-- <template #content>
                <img :src="banner.image" alt="">
            </template> -->
        </vueper-slide>
    </vueper-slides>
</template>

<script>
import { VueperSlides, VueperSlide } from 'vueperslides'
import 'vueperslides/dist/vueperslides.css'
export default {
    components: {
        VueperSlides,
        VueperSlide,
    },
    props: {
        banners: {
            type: Array,
            default: () => {
                return [
                    {
                        title: 'testing',
                        route: {
                            name: 'web.comic',
                            params: {comic: 1}
                        },
                        image: '/storage/media/banners/test.jpg'
                    },
                    {
                        title: 'testing',
                        route: {
                            name: 'web.comic',
                            params: {comic: 1}
                        },
                        image: '/storage/media/banners/test.jpg'
                    }
                ]
            }
        }
    },
    methods: {
        goToItem(route){
            this.$inertia.visit(route(route.name, route.params))
        }
    },
    setup() {
        const onSwiper = (swiper) => {
            console.log(swiper);
        };
        const onSlideChange = () => {
            console.log('slide change');
        };
        return {
            onSwiper,
            onSlideChange,
        };
    },
}
</script>
