<template>
    <div class="flex text-black">
        <div class="flex-shrink-0 mr-3">
            <img class="mt-2 rounded-full w-8 h-8 sm:w-10 sm:h-10" :src="comment.commenter.profile_photo_url" alt="">
        </div>
        <div class="flex-1 border rounded-lg px-4 py-2 sm:px-6 sm:py-4 leading-relaxed">
            <strong>{{comment.commenter.name}}</strong> <span class="text-xs text-gray-900">{{moment(comment.created_at).fromNow()}}</span>
            <p class="text-sm" v-html="comment.comment">
            </p>

            <div class="my-5 flex flex-row justify-between">
                <h4 v-if="comment.all_children_with_commenter.length > 0" class="uppercase tracking-wide text-gray-900 font-bold text-xs">Replies</h4>
                <div></div>
                <h4 class="uppercase tracking-wide font-bold text-xs text-blue-600" @click="toggleReplyBox">Reply</h4>
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
                    <div class="flex-1 bg-gray-100 rounded-lg px-4 py-2 sm:px-6 sm:py-4 leading-relaxed">
                        <strong>{{childComment.commenter.name}}</strong> <span class="text-xs text-gray-900">{{moment(comment.created_at).fromNow()}}</span>
                        <p class="text-sm" v-html="childComment.comment">
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
export default {
    name: 'comment',
    props: ['comment'],
    inject: ['swal'],
    data(){
        return {
            moment: null,
            replyBox: false,
            message: ''
        }
    },
    created(){
        this.moment = moment
    },
    methods:{
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
