<template>
    <app-layout>
        <div>
            <div class="">
                <div class="bg-gray-300 w-full h-10 border-t-2 border-b-2 border-black">
                    <div class="text-center text-lg pt-1">Favorited Comics</div>
                </div>
            </div>
            <div class="px-5 pt-5">
                <grid
                    :items="processItems(items.favorited, 'favorited')"
                    :config="config"
                    objectCategory="favorited"
                    @nextPage="nextPage"
                ></grid>
            </div>
        </div>
        <div>
            <div class="pt-5">
                <div class="bg-gray-300 w-full h-10 border-t-2 border-b-2 border-black">
                    <div class="text-center text-lg pt-1">Purchased Comics</div>
                </div>
            </div>
            <div class="px-5 pt-5">
                <grid
                    :items="processItems(items.purchased, 'purchased')"
                    :config="config"
                    objectCategory="purchased"
                    @nextPage="nextPage"
                ></grid>
            </div>
        </div>
        <div>
            <div class="pt-5">
                <div class="bg-gray-300 w-full h-10 border-t-2 border-b-2 border-black">
                    <div class="text-center text-lg pt-1">Subscribed Comics</div>
                </div>
            </div>
            <div class="px-5 py-5">
                <grid
                    :items="processItems(items.subscribed, 'subscribed')"
                    :config="config"
                    objectCategory="subscribed"
                    @nextPage="nextPage"
                ></grid>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import Grid from '../Components/Grid.vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    export default {
        name: 'mycomic',
        props: ['subscribed', 'favorited', 'purchased'],
        components: {
            Grid,
            AppLayout,
        },
        created() {
            // console.log(this.subscriptions)
            // this.shownTags.forEach((elem) => {
            //     this.comics[elem] = {}
            //     this.comics[elem].comics = []
            //     this.getComics(route('api.comics.list', { ...this.query, where_tag: elem }), elem)
            // })
            // this.getComics(route('api.comics.list', this.query), 'all')

            this.items.favorited = this.favorited.slice(0, 3)
            this.items.subscribed = this.subscribed.slice(0, 3)
            this.items.purchased = this.purchased.slice(0, 3);
            ['subscribed', 'favorited', 'purchased'].forEach((key) => {
                if(this.itemsCounts[key] >= this[key].length){
                    this.nextPages[key] = false
                }
            })
        },
        data() {
            return {
                items: {
                    favorited: [],
                    subscribed: [],
                    purchased: [],
                },
                itemsCounts: {
                    favorited: 3,
                    subscribed: 3,
                    purchased: 3,
                },
                nextPages: {
                    favorited: true,
                    subscribed: true,
                    purchased: true
                },
                shownTags: [
                    'ipsum',
                    'lorem'
                ],
                comics: {
                    all: {
                        comics: []
                    }
                },
                query: {
                    paginate: 14,
                    page: 1
                },
                config: {
                    image: 'cover_url',
                    title: 'title'
                },
            }
        },
        methods: {
            processItems(comicObjects, category) {
                let retVal = []
                comicObjects.forEach(element => {
                    retVal.push({
                        url: '/comic/' + element.id,
                        cover_url: element.cover_url,
                        title: element.title
                    })
                });
                return { items: retVal, nextPageUrl: this.nextPages[category] }
            },
            getComics(url, category) {
                axios.get(url)
                    .then((response) => {
                        if (!this.comics[category]) {
                            this.comics[category] = {}
                            this.comics[category].comics = response.data.data
                        } else {
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
            nextPage(category) {
                console.log(category)
                this.itemsCounts[category] += 3
                this.items[category] = this[category].slice(0, this.itemsCounts[category])
                if(this.itemsCounts[category] > this.items[category].length){
                    this.nextPages[category] = false
                }
            },
            prevPage(category) {

            }
        }
    }
</script>
