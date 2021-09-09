<template>
    <div class="flex text-black">
        <div class="flex-shrink-0 mr-3">
            <img class="mt-2 rounded-full w-8 h-8 sm:w-10 sm:h-10" :src="comment.commenter.profile_photo_url" alt="">
        </div>
        <div class="flex-1 border px-4 py-2 sm:px-6 sm:py-4 leading-relaxed">
            <strong>{{comment.commenter.name}}</strong> <span class="text-xs text-gray-900">{{moment(comment.created_at).fromNow()}}</span>
            <button v-if="comment.can_delete" @click="deleteComment(comment.id)" class="float-right inline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <p class="text-sm" v-html="comment.comment">
            </p>

            <div class="my-5 flex flex-row justify-between">
                <div v-if="comment.all_children_with_commenter.length > 0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <h4 class="inline uppercase tracking-wide text-gray-900 font-bold text-xs">
                        {{comment.all_children_with_commenter.length}} {{comment.all_children_with_commenter.length > 1 ? 'Replies' : 'Reply'}}
                    </h4>
                </div>
                <div>
                    <button @click="likeComment(comment.id, likes[comment.id])">
                        <svg :class="likes[comment.id] ? 'active' : 'inactive'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline animated" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            <!-- <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" /> -->
                        </svg>
                    </button>
                    <h4 class="inline uppercase tracking-wide text-gray-900 font-bold text-xs">
                        {{comment.rating}} {{comment.rating > 1 ? 'Likes' : 'Like'}}
                    </h4>
                </div>
                <div></div>
                <h4 class="uppercase tracking-wide font-bold text-xs text-blue-600 flex items-center" @click="toggleReplyBox">Reply</h4>
            </div>

            <form @submit.prevent="submit" v-if="replyBox">
                <div class="flex flex-wrap -mx-3 mb-4">
                    <h2 class="px-4 pb-2 text-gray-800 text-lg">Add a new comment</h2>
                    <div class="w-full md:w-full px-3 mb-2 mt-2">
                        <textarea v-model="message" class="text-black bg-gray-100 rounded border border-gray-400 leading-normal resize-none w-full h-20 py-2 px-3 font-medium placeholder-gray-700 focus:outline-none focus:bg-white" name="body" placeholder='Type Your Comment' required></textarea>
                    </div>
                    <div class="w-full md:w-full flex items-start md:w-full px-3">
                        <div class="-mr-1">
                            <input type='submit' class="bg-white text-gray-700 font-medium py-1 px-4 border border-gray-400 rounded-lg tracking-wide mr-1 hover:bg-gray-100" value='Post Comment'>
                        </div>
                    </div>
                </div>
            </form>

            <div class="space-y-4">
                <div v-for="(childComment, idx) in comment.all_children_with_commenter" class="flex" :key="'children-' + idx">
                    <div class="flex-shrink-0 mr-3">
                        <img class="mt-3 rounded-full w-6 h-6 sm:w-8 sm:h-8" :src="comment.commenter.profile_photo_url" alt="">
                    </div>
                    <div class="flex-1 bg-gray-100 rounded-lg px-2 py-2 sm:px-6 sm:py-4 leading-relaxed">
                        <strong>{{childComment.commenter.name}}</strong> <span class="text-xs text-gray-900">{{moment(comment.created_at).fromNow()}}</span>
                        <button v-if="childComment.can_delete"  @click="deleteComment(childComment.id)" class="float-right inline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <p class="text-sm" v-html="childComment.comment">
                        </p>
                        <div class="flex flex-row justify-between">
                            <div>
                                <button @click="likeComment(childComment.id, likes[childComment.id])">
                                    <svg :class="likes[childComment.id] ? 'active' : 'inactive'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline animated" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        <!-- <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" /> -->
                                    </svg>
                                </button>
                                <h4 class="inline uppercase tracking-wide text-gray-900 font-bold text-xs">
                                    {{childComment.rating}} {{childComment.rating > 1 ? 'Likes' : 'Like'}}
                                </h4>
                            </div>
                            <div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment, { isMoment } from 'moment'
export default {
    name: 'comment',
    props: ['comment'],
    inject: ['swal'],
    data(){
        return {
            moment: null,
            replyBox: false,
            message: '',
            likes: {},
        }
    },
    created(){
        this.moment = moment
        this.likes[this.comment.id] = this.comment.liked
        this.comment.all_children_with_commenter.forEach((el) => {
            this.likes[el.id] = el.liked
        })
    },
    methods:{
        likeComment(commentId, liked){
            let vote = true
            if(liked){
                vote = false
            }
            axios.post(route('api.comment.vote', {comment: commentId}), {
                vote: vote
            })
            .then((response) => {
                this.likes[commentId] = !liked
                if(this.comment.id === commentId){
                    this.comment.rating = response.data.rating
                }else{
                    let idx = this.comment.all_children_with_commenter.findIndex(el => el.id === commentId)
                    this.comment.all_children_with_commenter[idx].rating = response.data.rating
                    // this.comment.all_children_with_commenter[idx].liked = !vote
                }
            })
        },
        deleteComment(id){
            const swalWithBootstrapButtons = this.swal.mixin({
                customClass: {
                    confirmButton: 'bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded',
                    cancelButton: 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(route('api.comment.delete', {comment: id}))
                    .then((response) => {
                        this.emitter.emit('reloadComments')
                        swalWithBootstrapButtons.fire(
                            'Deleted!',
                            'Your comment has been deleted.',
                            'success'
                        )
                    })
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your comment is safe :)',
                        'error'
                    )
                }
            })
        },
        toggleReplyBox(){
            this.replyBox = !this.replyBox
        },
        submit(){
            axios.post(route('comments.reply', {comment: this.comment.id}), {
                message: this.message
            })
            .then((response) => {
                this.message = ''
                this.replyBox = false
                this.emitter.emit('reloadComments')
                return this.swal.fire({
                    icon: "success",
                    title: "Comment posted!",
                    text: "Comment posted succesful!",
                })
            })
        }
    }
}
</script>
<style scoped>
.active{
    fill:red;
}
.inactive{
    fill:transparent;
}
</style>
