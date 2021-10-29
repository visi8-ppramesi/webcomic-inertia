<template>
    <vueper-slides slide-image-inside class="w-full">
        <vueper-slide v-for="(banner, idx) in banners" :key="idx" :title="banner.title" :image="banner.image" :link="route(banner.route.name, banner.route.params)">
            <template #content>
                <img :src="banner.image" alt="">
            </template>
        </vueper-slide>
    </vueper-slides>
    <!-- <div class="p-7">
        <carousel :items-to-show="1">
            <slide class="h-full w-full" v-for="(banner, idx) in banners" :key="idx">
                <img :src="banner.image">
            </slide>

            <template #addons>
                <navigation />
                <pagination />
            </template>
        </carousel>
    </div> -->
</template>

<script>
import { VueperSlides, VueperSlide } from 'vueperslides'
import 'vueperslides/dist/vueperslides.css'
// import 'vue3-carousel/dist/carousel.css';
// import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';

export default {
    components: {
        VueperSlides,
        VueperSlide,
        // Carousel,
        // Slide,
        // Pagination,
        // Navigation
    },
    created(){
        console.log(this.banners)
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
