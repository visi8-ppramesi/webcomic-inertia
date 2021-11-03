<template>
    <app-layout>
        <div class="mb-3 text-white">
            <div class="px-5 py-5">
                <div>
                    <div>Popular</div>
                </div>
                <grid
                    :items="processItems(comics.all)"
                    :config="config"
                    objectCategory="all"
                    @nextPage="nextPage"
                ></grid>
            </div>
        </div>
        <!-- <horizontal-menu
            :items="processedGenres"
            :config="{
                title: 'name',
                link: 'link',
                selected: 'name'
            }"
            :selected="value"
        ></horizontal-menu> -->

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
    props: ['type', 'value'],
    created(){
        switch(this.type){
            case 'newest':
                this.title = 'Newest Comics'
                this.queryParams = {
                    paginate: 12,
                    page: 1,
                    sort_by_desc: 'created_at'
                }
                break;
            case 'tag':
                this.title = 'Comics Tagged ' + this.value.toUppercase()
                this.queryParams = {
                    paginate: 12,
                    page: 1,
                    where_tag: this.value
                }
                break;
            case 'genre':
                this.title = 'Comics Genre ' + this.value.toUppercase()
                this.queryParams = {
                    paginate: 12,
                    page: 1,
                    where_genre: this.value
                }
                break;
            case 'popular':
                this.title = 'Popular Comics'
                this.queryParams = {
                    paginate: 12,
                    page: 1,
                    sort_by_popular: 1
                }
                break;
            case 'recommended':
                this.title = 'Recommended For You'
                this.queryParams = {
                    paginate: 12,
                    page: 1,
                    recommended_comics: 1
                }
                break;
            default:
                this.title = 'All Comics'
                this.queryParams = {
                    paginate: 12,
                    page: 1
                }
                break;
        }

        this.getComics(route('api.comics.list', this.queryParams), 'all')
        // this.processedGenres = this.genres.map((el) => {
        //     let k = Object.assign({}, el)
        //     k.link = route('web.comics', {type: 'genre', value: el.name})
        //     return k
        // })
        // if(!_.isNull(this.type) && _.includes(['tag', 'genre', 'newest'], this.type)){

        // }else{
        //     // Promise.all([
        //     //     axios.get(route('api.tags')),
        //     //     axios.get(route('api.genres'))
        //     // ])
        //     // .then(axios.spread((tagsResponse, genresResponse) => {
        //     //     this.tags = tagsResponse.data
        //     //     this.genres = genresResponse.data
        //     // }))
        // }
    },
    methods: {
        processItems(comicObjects){
            let retVal = []
            comicObjects.comics.forEach(element => {
                retVal.push({
                    url: '/comic/' + element.id,
                    cover_url: element.cover_url,
                    title: element.title
                })
            })
            return {items: retVal, nextPageUrl: comicObjects.nextPageUrl}
        },
        getComics(url, category){
            axios.get(url)
            .then((response) => {
                if(!this.comics[category]){
                    this.comics[category] = {}
                    this.comics[category].comics = response.data.data
                }else{
                    this.comics[category].comics = this.comics[category].comics.concat(response.data.data)
                }
                this.comics[category].paginationData = response.data
                this.comics[category].prevDisabled = this.comics[category].paginationData.prev_page_url === null
                this.comics[category].nextDisabled = this.comics[category].paginationData.next_page_url === null
                this.comics[category].prevPageUrl = this.comics[category].paginationData.prev_page_url
                this.comics[category].nextPageUrl = this.comics[category].paginationData.next_page_url
            })
            .catch((error) => {
                //do error catching later
            })
        },
        nextPage(category){
            if(!this.comics[category].nextDisabled){
                this.getComics(this.comics[category].nextPageUrl, category)
            }
        },
    },
    data(){
        return {
            title: '',
            comics: {
                all: {
                    comics: []
                },
            },
            queryParams: {
                paginate: 12,
                page: 2
            },
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
