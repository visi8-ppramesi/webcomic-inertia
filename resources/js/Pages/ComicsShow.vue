<template>
    <app-layout>
        <div class="">
            <div class="bg-gray-300 w-full h-10 border-t-2 border-b-2 border-black">
                <div class="text-center text-lg pt-1">{{title}}</div>
            </div>
        </div>
        <div class="px-5 pt-5">
            <grid
                :items="processItems(items, 'favorited')"
                :config="config"
                objectCategory="favorited"
                @nextPage="nextPage"
            ></grid>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import Grid from '../Components/Grid.vue'
export default {
    name:'comics-show',
    components: {
        Grid,
        AppLayout,
    },
    props: ['title'],
    methods: {
        processItems(item, category){

        }
    },
    data(){
        return {

        }
    }
}
</script>

<style scoped>

</style>
