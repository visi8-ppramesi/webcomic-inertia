<template>
    <app-layout>
        <div class="relative flex w-full flex-wrap items-stretch">
            <input @keyup.enter="goSearch" v-model="searchQuery" type="text" placeholder="search" class="px-3 py-3 placeholder-blueGray-300 text-blueGray-600 relative bg-white bg-white text-sm border-0 shadow outline-none focus:outline-none focus:ring w-full pr-10" />
            <button @click="goSearch" class="z-10 h-full leading-snug font-normal absolute text-center text-blueGray-300 absolute bg-transparent rounded text-base items-center justify-center w-8 right-0 pr-3 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>
        <div class="text-center text-white mt-3" v-if="searching">search result of "{{query}}"</div>
        <div class="px-5 py-5 bg-gradient-to-t  to-indigo-900 from-purple-900" v-if="searching">
            <div class="mb-3 text-white">
                <div>
                    <div class="text-white float-right">More</div>
                    <div>Comic Search Results</div>
                </div>
                <mq-responsive target="sm-" tag="span">
                    <div>
                        <horizontal-slider
                            :items="processToHorizontalSlider(comics)"
                            :config="config"
                            objectCategory="all"
                            @nextPage="nextPage"
                        ></horizontal-slider>
                    </div>
                </mq-responsive>
                <mq-responsive target="md+" tag="span">
                    <div>
                        <grid
                            :items="processToHorizontalSlider(comics)"
                            :config="config"
                            objectCategory="all"
                            @nextPage="nextPage"
                        ></grid>
                    </div>
                </mq-responsive>
            </div>
        </div>
    </app-layout>
</template>

<script>
import Grid from '../Components/Grid.vue'
import HorizontalSlider from '../Components/HorizontalSlider.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
export default {
    name: 'search-show',
    data(){
        return {
            searchQuery: '',
            searching: false,
            comics: [],
            page: 1,
            config: {
                image: 'cover_url',
                title: 'title'
            }
        }
    },
    components: {
        Grid,
        HorizontalSlider,
        AppLayout
    },
    props: ['results', 'query'],
    created(){
        this.searchQuery = this.query
        if(_.has(this.$page.props, 'results')){
            this.searching = true
            this.comics = this.$page.props.results.comics
        }
    },
    methods: {
        fetchSearchResult(page){
            axios.get(route('api.search', {
                search: this.query,
                page: page
            }))
            .then((response) => {
                let tempComics = this.comics.data
                tempComics = tempComics.concat(response.data.results.comics.data)
                this.comics = response.data.results.comics
                this.comics.data = tempComics
            })
        },
        nextPage(category){
            this.page += 1
            this.fetchSearchResult(this.page)
        },
        processToAuthorHorizontalSlider(authorObjects){
            let retVal = []
            console.log(authorObjects)
            authorObjects.data.forEach(element => {
                retVal.push({
                    url: '/author/' + element.id,
                    cover_url: element.profile_picture_url,
                    title: element.name
                })
            });
            return {items: retVal, nextPageUrl: authorObjects.next_page_url}
        },
        processToHorizontalSlider(comicObjects){
            let retVal = []
            comicObjects.data.forEach(element => {
                retVal.push({
                    url: '/comic/' + element.id,
                    cover_url: element.cover_url,
                    title: element.title
                })
            });
            return {items: retVal, nextPageUrl: comicObjects.next_page_url}
        },
        goSearch(){
            this.$inertia.visit(route('web.search', {search: this.searchQuery}))
        }
    }
}
</script>

<style scoped>

</style>
