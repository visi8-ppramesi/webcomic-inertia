<template>
    <app-layout>
        <div>
            <div class="description-block text-white flex flex-col justify-end p-5" :style="'background-image:linear-gradient(to bottom, rgba(245, 246, 252, 0), rgb(0 0 0 / 73%)), url(' + comic.cover_url + ');'"><!-- top block -->
                <div>
                    <template v-for="(genre, idx) in genres" :key="'genre-' + idx">
                        <Link :href="route('web.search', {search: genre})">{{genre}}<span v-if="idx < genres.length - 1" :key="'comma-' + idx">, </span></Link>
                    </template>
                </div><!-- make it linkable later -->
                <div class="flex flex-row justify-between">
                    <div class="text-2xl font-bold w-2/3">{{comic.title}}</div>
                    <button :class="{'bg-transparent ring-white ring-inset ring-2': subscribed, 'bg-green-400': !subscribed}" class="animated w-fit-content h-fit-content inline-flex items-center justify-center px-2 py-1 rounded-full text-gray-50 bg-green-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="subscribeComic">
                        Subscribe
                    </button>
                </div>
                <div>
                    <template v-for="(author, idx) in authors" :key="'author-' + idx">
                        <Link :href="route('web.author', {author: author.id})">{{author.name}}<span v-if="idx < authors.length - 1" :key="'comma-' + idx">, </span></Link>
                    </template>
                </div>
                <div class="text-sm">{{comic.description}}</div>
                <div class="flex mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    <div class="text-sm px-2">{{helpers.compactFormatter(comic.views)}}</div>
                    <div class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <div class="text-sm px-2">{{helpers.compactFormatter(comic.favorites_count)}}</div>
                    </div>
                </div>
                <div class="flex flex-row content-center justify-between">
                    <!-- <button class="text-sm mt-3 inline-flex items-center justify-center px-2 py-1 rounded-full text-gray-50 bg-green-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="continueReading(true)">Subscribe</button> -->
                    <template v-if="purchased">
                        <button class="text-sm mt-3 inline-flex items-center justify-center px-2 py-2 rounded-full text-gray-50 bg-green-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="continueReading(true)">View with AR</button>
                        <button class="text-sm mt-3 inline-flex items-center justify-center px-2 py-2 rounded-full text-gray-50 bg-green-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" v-if="isEmpty(bookmark)" @click="startReading">Start Reading</button>
                        <button class="text-sm mt-3 inline-flex items-center justify-center px-2 py-2 rounded-full text-gray-50 bg-green-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" v-else @click="continueReading(false)">Continue Reading</button>
                    </template>
                    <!-- <template v-else>
                        <button class="mt-3 inline-flex items-center justify-center px-2 py-1 rounded-full text-gray-50 bg-green-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="openModal">Buy Comic</button>
                    </template> -->
                    <button class="mt-3 inline-flex items-center justify-center px-2 py-2 rounded-full text-gray-50 bg-green-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="favoriteComic">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path class="animated" :class="favorited ? 'fill-white' : 'fill-none'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="divide-y divide-black">
                <div class="flex flex-row h-20 bg-indigo-800 text-white" v-for="(chapter, idx) in chapters" :key="'chapter-'+idx">
                    <div class="flex-none w-1/5 lg:w-24">
                        <img class="h-full w-full" :src="chapter.image_url" alt="">
                    </div>
                    <!-- <div class="flex-grow flex flex-col p-3 w-2/5 lg:w-2" @click="goToChapter(chapter.id)"> -->
                    <div class="flex-grow flex flex-col py-3 pl-3 w-2/5 lg:w-2">
                        <div class="w-100">
                            <span class="text-sm">Ep. {{chapter.chapter}}</span>
                            <span class="text-xs ml-2">{{chapter.release_date}}</span>
                        </div>
                        <div class="flex flex-row mt-2">
                            <div class="flex flex-row">
                                <button @click="favoriteChapter(chapter.id)">
                                    <svg :class="chapterFavorited.includes(chapter.id) ? 'fill-white' : 'fill-none'" xmlns="http://www.w3.org/2000/svg" class="animated h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                                <div class="text-xs px-0.5">{{helpers.compactFormatter(chapter.favorites_count)}}</div>
                            </div>
                            <div class="flex flex-row ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <div class="text-xs px-0.5">{{helpers.compactFormatter(chapter.views)}}</div>
                            </div>
                            <div v-if="chapter.token_price == 0 && false">
                                free!
                            </div>
                        </div>
                    </div>
                    <div class="w-2/5 flex justify-center items-center" >
                        <button v-if="checkChapterUnpurchased(purchaseObj.chapters, chapter)" class="text-xs items-center min-h-8 w-116  p-2 rounded-lg text-gray-50 bg-purple-500 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="openModal(chapter)">Buy Ep. {{chapter.chapter}}</button>
                        <template v-else>
                            <button v-if="checkArPuchased(chapter.id)" class="text-xs items-center h-auto w-116  p-2 rounded-lg text-gray-50 bg-green-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="goToChapter(chapter, true)">Read Ep. {{chapter.chapter}} With AR</button>
                            <button v-else class="text-xs items-center h-auto w-116  p-2 rounded-lg text-gray-50 bg-green-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="goToChapter(chapter, false)">Read Ep. {{chapter.chapter}}</button>
                        </template>
                        <!-- <button @click="openModal(preview.chapter)">Buy Episode</button> -->
                    </div>
                </div>
            </div>
            <div class="text-white">
                <add-new-comment
                    :commentKey="$page.props.comment_key"
                />
                <comments class="mt-5" :comments="comments"/>
                <!-- <div class="flex flex-row mb-4 comment-container" v-for="(comment, idx) in comments" :key="'comm' + idx">
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
                </div> -->
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
            <div v-show="false">
                <Link class="purchase-token-link" :href="route('web.mytokens')">Purchase more tokens!</Link>
            </div>
        </div>
    </app-layout>
</template>

<script>
import Modal from '../Components/Modal.vue'
import AddNewComment from '../Components/AddNewComment.vue'
import Comments from '../Components/Comments.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { usePage } from '@inertiajs/inertia-vue3'

export default {
    name: 'comic-show',
    components: {
        Modal,
        AppLayout,
        AddNewComment,
        Comments
    },
    props: ['comic'],
    inject: ['swal', 'helpers'],
    data(){
        return {
            showArOption: false,
            favorited: false,
            chapterFavorited: {},
            subscribed: false,
            arSelected: false,
            user: null,
            modal: false,
            comic: {},
            bookmark: {},
            purchased: false,
            purchaseObj: {
                ar:[]
            },
            authors: [],
            tags: [],
            chapters: [],
            genres: [],
            episodeModal: null,
            userIcon: require('../../icons/user-icon.png'),
            tokenPrice: 0,
            comments: []
        }
    },
    created(){
        if(_.has(route().params, 'error') && route().params.error == 'unpurchased'){
            this.swal.fire({
                icon: "error",
                title: "You don\'t own this chapter!",
                text: "Please purchase the chapter to read it!",
            })
        }
        this.comments = this.$page.props.comments
        this.comic = usePage().props.value.comic
        this.tags = JSON.parse(this.comic.tags)
        this.genres = JSON.parse(this.comic.genres)
        let self = this
        this.emitter.on('closeModal', function(){
            self.modal = false
        })
        this.user = usePage().props.value.user

        this.checkPurchased()
        .then((response) => {
            this.purchaseObj = response.data
            this.purchased = Object.keys({...response.data}).length > 0
            return axios.get(route('api.comic.check.bookmark', {comic: this.comic.id}))
        })
        .then((response) => {
            this.bookmark = response.data
        })
        this.chapters = this.comic.chapters
        console.log(JSON.parse(this.user.favorites))
        this.subscribed = _.includes(JSON.parse(this.user.subscriptions).map(x => +x), this.comic.id)
        let favObj = JSON.parse(this.user.favorites)
        this.favorited = _.includes(favObj['comics'].map(x => +x), this.comic.id)
        this.chapterFavorited = favObj['chapters']
        this.authors = this.comic.authors
        this.emitter.on('reloadComments', this.reloadComments)

        localStorage.setItem('lastPage', JSON.stringify({routeName: 'web.comic', params: {comic: this.comic.id}}))

        // axios.get(route('api.comic.check.purchased', {comicId: this.comic.id}))
        // .then((response) => {
        //     this.purchaseObj = response.data
        //     this.purchased = Object.keys({...response.data}).length > 0
        //     return axios.get(route('api.comic.check.bookmark', {comicId: this.comic.id}))
        // })
        // .then((response) => {
        //     this.bookmark = {...response.data}
        // })
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
        reloadComments(){
            return axios.get(route('api.comic.fetch.comments', {comic: this.comic.id}))
            .then((response) => {
                this.comments = response.data
            })
        },
        subscribeComic(){
            return axios.get(route('api.comic.subscribe', {comic: this.comic.id}))
            .then((response) => {
                this.subscribed = _.includes(response.data.map(x => +x), this.comic.id)
            })
        },
        favoriteComic(){
            return axios.get(route('api.comic.favorite', {comic: this.comic.id}))
            .then((response) => {
                this.favorited = _.includes(response.data.comics.map(x => +x), this.comic.id)
            })
        },
        favoriteChapter(chapterId){
            return axios.get(route('api.chapter.favorite', {chapter: chapterId}))
            .then((response) => {
                this.chapterFavorited = response.data.chapters
            })
        },
        checkPurchased(){
            return axios.get(route('api.comic.check.purchased', {comic: this.comic.id}))
        },
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
        checkChapterUnpurchased(chapters, chapter){
            return !_.includes(chapters, chapter.id) && chapter.token_price != 0
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
            console.log(this.comic.chapters[0].id)
            this.$inertia.visit(route('web.chapter', {comic: usePage().props.value.comic.id, chapter: this.comic.chapters[0].id, ar: false}))
            // this.$router.push({name: 'pageShow', params: {
            //     comicId: this.$route.params.comicId,
            //     chapter: this.previews[0].chapter,
            // }})
        },
        continueReading(ar = false){
            let chapter = this.bookmark || this.chapters[0].id
            this.$inertia.visit(route('web.chapter', {comic: usePage().props.value.comic.id, chapter: chapter, ar: ar}))
            // this.$router.push({name: 'pageShow', params: {
            //     comicId: this.$route.params.comicId,
            //     chapter: chapter
            // },
            // query:{
            //     ar: ar
            // }})
        },
        isEmpty(item){
            // return _.isEmpty(item)
            return item === null
        },
        gotToTag(){

        },
        goToChapter(chapter, ar = false){
            if(!this.checkChapterUnpurchased(this.purchaseObj.chapters, chapter)){
                this.$inertia.visit(route('web.chapter', {comic: usePage().props.value.comic.id, chapter: chapter.id, ar: ar}))
            }
            // this.$router.push({name: 'pageShow', params: {
            //     comicId: this.$route.params.comicId,
            //     chapter: chapter,
            // },
            // query:{
            //     ar: ar
            // }})
        },
        checkArPuchased(chapter){
            return !_.isEmpty(this.purchaseObj) && this.purchaseObj.ar.map(x => +x).includes(chapter)
        },
        purchaseChapter(){
            axios.post(route('api.chapter.purchase'), {
                ar: this.arSelected,
                chapter: this.episodeModal.id
            })
            .then((response) => {
                let status = response.data.status
                if(status === 0){
                    this.modal = false
                    this.episodeModal = null
                    this.swal.fire({
                        icon: "error",
                        title: "Not Enough Token",
                        text: "You don't have enough token! Please purchase more tokens!",
                        footer: document.getElementsByClassName('purchase-token-link')[0].parentElement.innerHTML
                    })
                    .then((e) => {
                        console.log(e)
                    })
                }else if(status === -1){
                    this.modal = false
                    this.episodeModal = null
                    this.swal.fire({
                        icon: "info",
                        title: "Already purchased",
                        text: "Chapter already purchased!",
                    });
                }else{
                    axios.get(route('api.user.check'))
                    .then((response) => {
                        this.user = response.data
                    })

                    this.checkPurchased()
                    .then((response) => {
                        this.purchaseObj = response.data
                        this.purchased = Object.keys({...response.data}).length > 0

                        this.swal.fire({
                            icon: "success",
                            title: "Purchase successful!",
                            text: "Chapter purchased!",
                        });

                        this.modal = false
                        this.episodeModal = null
                    })
                }
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
.animated{
    -webkit-transition: all 0.2s 0s ease;
    -moz-transition: all 0.2s 0s ease;
    -o-transition: all 0.2s 0s ease;
    transition: all 0.2s 0s ease;
}
.fill-white{
    fill:white;
}
.fill-none{
    fill:transparent;
}
</style>
