<template>
    <app-layout>
        <div>
            <div class="description-block text-white flex flex-col justify-end p-5" :style="'background-image:linear-gradient(to bottom, rgba(245, 246, 252, 0), rgb(0 0 0 / 73%)), url(' + comic.cover_url + ');'"><!-- top block -->
                <div>
                    <template v-for="(genre, idx) in genres" :key="'genre-' + idx">
                        <router-link :to="{name: 'search', query: {search: genre}}">{{genre}}<span v-if="idx < genres.length - 1" :key="'comma-' + idx">, </span></router-link>
                    </template>
                </div><!-- make it linkable later -->
                <div class="text-2xl font-bold">{{comic.title}}</div>
                <div>
                    <template v-for="(author, idx) in authors" :key="'author-' + idx">
                        <router-link :to="{name: 'authorShow', params: {authorId: author.id}}">{{author.name}}<span v-if="idx < authors.length - 1" :key="'comma-' + idx">, </span></router-link>
                    </template>
                </div>
                <div class="text-sm">{{comic.description}}</div>
                <div class="flex flex-row content-center justify-between">
                    <template v-if="purchased">
                        <button class="text-sm mt-3 mt-3 inline-flex items-center justify-center p-2 rounded-full text-gray-50 bg-green-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="continueReading(true)">View with AR</button>
                        <button class="text-sm mt-3 inline-flex items-center justify-center p-2 rounded-full text-gray-50 bg-green-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" v-if="isEmpty(bookmark)" @click="startReading">Start Reading</button>
                        <button class="text-sm mt-3 inline-flex items-center justify-center p-2 rounded-full text-gray-50 bg-green-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" v-else @click="continueReading(false)">Continue Reading</button>
                    </template>
                    <!-- <template v-else>
                        <button class="mt-3 inline-flex items-center justify-center p-2 rounded-full text-gray-50 bg-green-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="openModal">Buy Comic</button>
                    </template> -->
                    <button class="mt-3 inline-flex items-center justify-center p-2 rounded-full text-gray-50 bg-green-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="favorite">Favorite</button>
                </div>
            </div>
            <div class="divide-y divide-black">
                <div class="flex flex-row h-20 bg-indigo-800 text-white" v-for="(chapter, idx) in chapters" :key="'chapter-'+idx">
                    <div class="flex-none w-1/5 lg:w-24">
                        <img class="h-20" :src="chapter.image_url" alt="">
                    </div>
                    <div class="flex-grow flex flex-col p-3 w-2/5 lg:w-2" @click="goToChapter(chapter.chapter)">
                        <div>Ep. {{chapter.chapter}}</div>
                        <div class="flex flex-row w-100">
                            <div class="mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <div class="text-xs">{{chapter.release_date}}</div>
                        </div>
                    </div>
                    <div class="w-2/5 flex justify-end items-end mb-7 mr-5" >
                        <button v-if="checkChapter(purchaseObj.chapters, chapter.id)" class="text-xs items-center min-h-8 w-116  p-2 rounded-lg text-gray-50 bg-green-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="openModal(chapter)">Buy Ep. {{chapter.chapter}}</button>
                        <template v-else>
                            <button class="text-xs items-center h-auto w-116  p-2 rounded-lg text-gray-50 bg-green-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="goToChapter(chapter.chapter, true)">Read Ep. {{chapter.chapter}} With AR</button>
                        </template>
                        <!-- <button @click="openModal(preview.chapter)">Buy Episode</button> -->
                    </div>
                </div>
            </div>
            <div class="p-5 text-white">
                <div class="mb-5">
                    Comments:
                </div>
                <div class="flex flex-row mb-4 comment-container" v-for="(comment, idx) in comments" :key="'comm' + idx">
                    <div class="comment-profile rounded-xl border border-gray-100">
                        <img :src="userIcon.default" alt="">
                    </div>
                    <div class="comment-textbox rounded-xl border border-gray-100 p-5 bg-gray-600 bg-opacity-50">
                        <div>
                            {{comment.username}}:
                        </div>
                        <div>
                            {{comment.comment}}
                        </div>
                    </div>
                </div>
            </div>
            <modal v-model="modal">
                <template v-slot:body>
                    <div class="p-6 flex flex-col content-center justify-center">
                        <img :src="episodeModal.image_url" class="h-1/2 rounded-lg lg:h-96 lg:w-full" />
                        <div class="lg:p-2">Harga: {{tokenPrice}} Token</div>
                        <div class="lg:p-2">Token kamu: {{user.total_tokens}}</div>
                        <div class="lg:p-2">Apakah kamu mau membeli Ep. {{episodeModal.chapter}}?</div>
                        <div v-if="showArOption" class="lg:p-2">
                            <input @change="adjustPrice" type="checkbox" class="form-checkbox" v-model="arSelected">
                            <span class="ml-2">Beli AR</span>
                        </div>
                    </div>
                </template>
                <template v-slot:footer>
                    <div class="flex h-16 bg-purple-600 bg-opacity-25">
                        <button @click="closeModal" class="h-100 w-1/2">Cancel</button>
                        <!-- <button @click="addToCart" class="h-100 w-1/3">Add to Cart</button> -->
                        <button @click="purchaseChapter(episodeModal.id)" class="h-100 w-1/2">Beli Dengan Token</button>
                    </div>
                </template>
            </modal>
        </div>
    </app-layout>
</template>

<script>
import Modal from '../Components/Modal.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { usePage } from '@inertiajs/inertia-vue3'

export default {
    name: 'comic-show',
    components: {
        Modal,
        AppLayout
    },
    props: ['comic'],
    data(){
        return {
            showArOption: false,
            arSelected: false,
            user: null,
            modal: false,
            comic: {},
            bookmark: {},
            purchased: false,
            purchaseObj: {},
            authors: [],
            tags: [],
            chapters: [],
            genres: [],
            episodeModal: null,
            userIcon: require('../../icons/user-icon.png'),
            tokenPrice: 0,
            comments: [
                {
                    username: 'wahyu1989',
                    comment: 'wow this is really good!'
                },
                {
                    username: 'santo0813',
                    comment: 'yes this is good'
                },
                {
                    username: 'santo0813',
                    comment: 'actually this is really good'
                },
                {
                    username: 'santi',
                    comment: 'i agree'
                }
            ]
        }
    },
    created(){
        this.comic = usePage().props.value.comic
        this.chapters = this.comic.chapters
        let self = this
        this.emitter.on('closeModal', function(){
            self.modal = false
        })
        this.user = usePage().props.value.user
        axios.get(route('api.comic.check.purchased', {comicId: this.comic.id}))
        .then((response) => {
            this.purchaseObj = response.data
            this.purchased = Object.keys({...response.data}).length > 0
            return axios.get(route('api.comic.check.bookmark', {comicId: this.comic.id}))
        })
        .then((response) => {
            this.bookmark = {...response.data}
        })
        // axios.get(route('api.comic.get.previews', {comicId: this.$route.params.comicId}))
        // .then((response) => {
        //     this.previews = response.data
        // })
        // axios.get(route('api.comic.show', {comic: this.$route.params.comicId}))
        // .then((response) => {
        //     this.comic = response.data
        //     this.tags = JSON.parse(this.comic.tags)
        //     this.genres = JSON.parse(this.comic.genres)
        //     this.parseAuthors()
        //     return axios.get(route('api.comic.check.purchased', {comicId: this.$route.params.comicId}))
        // })
        // .then((response) => {
        //     this.purchaseObj = response.data
        //     this.purchased = Object.keys({...response.data}).length > 0
        //     return axios.get(route('api.comic.check.bookmark', {comicId: this.$route.params.comicId}))
        // })
        // .then((response) => {
        //     this.bookmark = {...response.data}
        // })
        // .catch((error) => {
        //     this.$router.push({ name: 'notFound' })
        // })
    },
    methods: {
        closeModal(){
            this.modal = false
            this.episodeModal = null
        },
        adjustPrice(){
            if(this.arSelected){
                this.tokenPrice = this.episodeModal.token_price_ar
            }else{
                this.tokenPrice = this.episodeModal.token_price
            }
        },
        checkChapter(chapters, chapter){
            return !_.includes(chapters, chapter)
        },
        addToCart(){
            let cart = JSON.parse(localStorage.getItem('cart') || '{}')
            let comicId = usePage().props.value.comic.id
            if(comicId in cart){
                cart[comicId].push(this.episodeModal.id)
            }else{
                cart[comicId] = [this.episodeModal.id]
            }
            cart[comicId] = _.uniq(cart[comicId])
            localStorage.setItem('cart', JSON.stringify(cart))
            this.episodeModal = null
            this.modal = false
            this.emitter.emit('cartAddItem')
        },
        parseAuthors(){
            this.comic.authors.forEach(element => {
                this.authors.push({
                    name: element.name,
                    id: element.id
                })
            });
        },
        openModal(chapter){
            axios.get(route('api.chapter.check.ar', {chapter: chapter.id}))
            .then((response) => {
                this.showArOption = response.data
                this.episodeModal = chapter
                this.modal = true
                this.arSelected = false
                this.tokenPrice = this.episodeModal.token_price
            })
        },
        purchaseComic(){
            // let cart = JSON.parse(localStorage.getItem('cart') || '[]')
            // cart.push(this.$route.params.comicId)
            // localStorage.setItem('cart', JSON.stringify(cart))
            this.addToCart()
            // this.$router.push({name: 'paymentShow'})
            this.$inertia.visit(route('web.payment'))
        },
        startReading(){
            this.$inertia.visit(route('web.page', {comic: usePage().props.value.comic.id, chapter: this.comic.chapters[0].id, ar: ar}))
            // this.$router.push({name: 'pageShow', params: {
            //     comicId: this.$route.params.comicId,
            //     chapter: this.previews[0].chapter,
            // }})
        },
        continueReading(ar = false){
            let chapter = this.bookmark.chapter || this.previews[0].chapter
            this.$inertia.visit(route('web.page', {comic: usePage().props.value.comic.id, chapter: chapter, ar: ar}))
            // this.$router.push({name: 'pageShow', params: {
            //     comicId: this.$route.params.comicId,
            //     chapter: chapter
            // },
            // query:{
            //     ar: ar
            // }})
        },
        isEmpty(item){
            return _.isEmpty(item)
        },
        gotToTag(){

        },
        favorite(){

        },
        goToChapter(chapter, ar = false){
            this.$inertia.visit(route('web.page', {comic: usePage().props.value.comic.id, chapter: chapter, ar: ar}))
            // this.$router.push({name: 'pageShow', params: {
            //     comicId: this.$route.params.comicId,
            //     chapter: chapter,
            // },
            // query:{
            //     ar: ar
            // }})
        },
        purchaseChapter(){
            axios.post(route('api.chapter.purchase'), {
                ar: this.arSelected,
                chapter: this.episodeModal.id
            })
            .then((response) => {
                let status = response.data
            })
        }
    }
}
</script>

<style>
.description-block{
    height: calc(100vh - 64px);
    background-size: cover;
    background-position: center;
}
.comment-textbox{
    width: calc(100% - 60px);
    padding-left: 35px;
}
.comment-profile{
    width: 60px;
    height: 60px;
    position: relative;
    top: -10px;
    left: 17px;
    background-color: white;
    overflow:hidden;
}
.comment-container{
    margin-left:-17px;
}
</style>
