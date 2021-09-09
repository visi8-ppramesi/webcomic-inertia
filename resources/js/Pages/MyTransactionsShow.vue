<template>
     <app-layout>
        <div class="bg-white rounded h-screen">
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
                                    <div class="grid grid-cols-7 pt-3 pb-1">
                                        <div class="col-span-1 text-sm">Kara Book</div>
                                        <div class="flex items-center justify-center col-span-3 text-sm">Eps. 3</div>
                                        <div class="flex items-center justify-center col-span-2 text-sm">09-09-2021</div>
                                        <div class="flex items-center justify-center col-span-1 text-sm">100</div>
                                    </div>
                                    <div class="grid grid-cols-7 pt-3 pb-1">
                                        <div class="col-span-1 text-sm">Super Book</div>
                                        <div class="flex items-center justify-center col-span-3 text-sm">Eps. 3</div>
                                        <div class="flex items-center justify-center col-span-2 text-sm">09-09-2021</div>
                                    <div class="flex items-center justify-center col-span-1 text-sm">60</div>
                                </div>
                                <div class="grid grid-cols-7 pt-3 pb-1">
                                    <div class="col-span-1 text-sm">Magic Book</div>
                                    <div class="flex items-center justify-center col-span-3 text-sm">Eps. 3</div>
                                    <div class="flex items-center justify-center col-span-2 text-sm">09-09-2021</div>
                                    <div class="flex items-center justify-center col-span-1 text-sm">50</div>
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
                                <div class="grid grid-cols-3 pt-3 pb-1">
                                    <div class=" col-span-1 text-sm">150 Token</div>
                                    <div class="flex items-center justify-center col-span-1 text-sm">09-09-2021</div>
                                    <div class="flex items-end justify-end col-span-1 text-sm">Rp. 10.000</div>
                                </div>
                                <div class="grid grid-cols-3 pt-3 pb-1">
                                    <div class="col-span-1 text-sm">100 Token</div>
                                    <div class="flex items-center justify-center col-span-1 text-sm">09-09-2021</div>
                                    <div class="flex items-end justify-end col-span-1 text-sm">Rp. 7000</div>
                                </div>
                                <div class="grid grid-cols-3 pt-3 pb-1">
                                    <div class="col-span-1 text-sm">50 Token</div>
                                    <div class="flex items-center justify-center col-span-1 text-sm">09-09-2021</div>
                                    <div class="flex items-end justify-end col-span-1 text-sm">Rp. 5.000</div>
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

}
</script>
