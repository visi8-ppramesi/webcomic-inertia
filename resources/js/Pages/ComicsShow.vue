<template>
    <app-layout>
        <horizontal-menu
            :items="processedGenres"
            :config="{
                title: 'name',
                link: 'link',
                selected: 'name'
            }"
            :selected="value"
        ></horizontal-menu>
        <div class="px-5 py-5">
            <!-- <grid
                :items="processItems(comics)"
                :config="config"
                objectCategory="favorited"
            ></grid> -->
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import Grid from '../Components/Grid.vue'
import HorizontalMenu from '../Components/HorizontalMenu.vue'
export default {
    name:'comics-show',
    components: {
        Grid,
        AppLayout,
        HorizontalMenu
    },
    props: ['type', 'value', 'genres', 'comics'],
    created(){
        this.processedGenres = this.genres.map((el) => {
            let k = Object.assign({}, el)
            k.link = route('web.comics', {type: 'genre', value: el.name})
            return k
        })
        if(!_.isNull(this.type) && _.includes(['tag', 'genre', 'newest'], this.type)){

        }else{
            // Promise.all([
            //     axios.get(route('api.tags')),
            //     axios.get(route('api.genres'))
            // ])
            // .then(axios.spread((tagsResponse, genresResponse) => {
            //     this.tags = tagsResponse.data
            //     this.genres = genresResponse.data
            // }))
        }
    },
    methods: {
        processItems(item, category){
            return {
                items: item
            }
        }
    },
    data(){
        return {
            config: {
                image: 'cover_url',
                title: 'title'
            },
            tags: [],
            processedGenres: [],
        }
    }
}
</script>

<style scoped>

</style>
