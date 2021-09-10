<template>
     <app-layout>
        <div class="bg-white rounded">
            <div class="font-bold text-xl px-5 pt-3 lg:px-5 lg:pt-5">Book Purchase History</div>
            <div class="px-5">
                <div class="flex items-center justify-center border-2 rounded shadow-lg">
                    <div class="bg-white rounded w-full my-5">
                        <div class="divide-y divide-2 divide-black px-2">
                            <div class="grid grid-cols-7 pt-3 pb-1">
                                <div class="col-span-1 text-sm">Name</div>
                                <div class="flex items-center justify-center col-span-3 text-sm">Description</div>
                                <div class="flex items-center justify-center col-span-2 text-sm">Date</div>
                                <div class="flex items-end justify-end col-span-1 text-sm">Token</div>
                            </div>
                            <div>
                                <div class="grid grid-cols-7 pt-3 pb-1" v-for="(cpt, idx) in purchase_chapters" :key="'cpt-' + idx">
                                    <div class="col-span-1 text-sm">{{cpt.transactionable.comic.title}}</div>
                                    <div class="flex items-center justify-center col-span-3 text-sm">Ep. {{cpt.transactionable.chapter}}</div>
                                    <div class="flex items-center justify-center col-span-2 text-sm">{{helpers.formatDate(cpt.created_at)}}</div>
                                    <div class="flex items-center justify-center col-span-1 text-sm">{{cpt.token_amount}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="font-bold text-xl px-5 pt-3 lg:px-5 lg:pt-5">Token Purchase History</div>
            <div class="px-5 pb-10">
                <div class="flex items-center justify-center border-2 rounded shadow-lg">
                    <div class="bg-white rounded w-full my-5">
                        <div class="divide-y divide-2 divide-black px-2">
                            <div class="grid grid-cols-3 pt-3 pb-1">
                                <div class=" col-span-1 text-sm">Token</div>
                                <div class="flex items-center justify-center col-span-1 text-sm">Date</div>
                                <div class="flex items-end justify-end col-span-1 text-sm">Price</div>
                            </div>
                            <div>
                                <div class="grid grid-cols-3 pt-3 pb-1" v-for="(tkn, idx) in purchase_tokens" :key="'tkn-' + idx">
                                    <div class=" col-span-1 text-sm">{{tkn.token_amount}} Token</div>
                                    <div class="flex items-center justify-center col-span-1 text-sm">{{helpers.formatDate(tkn.created_at)}}</div>
                                    <div class="flex items-end justify-end col-span-1 text-sm">{{(descriptorObj[tkn.id].money_value).toLocaleString('id-ID', {style: 'currency', currency: 'IDR'})}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout.vue'
    export default {
    name:'my-transactions-show',
    components:{
        AppLayout
    },
    inject: ['helpers'],
    props: ['purchase_tokens', 'purchase_chapters'],
    data(){
        return {
            descriptorObj: {},
        }
    },
    created(){
        this.purchase_tokens.forEach((el) => {
            this.descriptorObj[el.id] = JSON.parse(el.descriptor)
        })
    },
}
</script>
