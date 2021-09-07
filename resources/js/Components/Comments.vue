<template>
    <div class="antialiased mx-auto max-w-screen-sm w-full bg-white px-4 pt-2 mt-5 pb-3">
        <h3 class="pt-3 pb-2 text-gray-800 text-lg">Comments</h3>
        <div class="space-y-4">
            <comment v-for="(comment, idx) in comments" :key="'comment-' + idx" :comment="comment"/>
        </div>
    </div>
</template>

<script>
import Comment from './Comment.vue'
export default {
    components: {
        Comment
    },
    name: 'comments',
    props: ['comments'],
    created(){
    }
}
</script>
