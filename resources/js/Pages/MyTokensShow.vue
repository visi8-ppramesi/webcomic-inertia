<template>
    <app-layout>
        <div class="bg-white h-full">
            <div class="flex items-center justify-center pt-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z" />
                    <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z" />
                    <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z" />
                </svg>
                <div class="px-2 mt-2">You have {{$page.props.user.total_tokens || 0}} tokens</div>
            </div>
            <div>
                <ul class="divide-y divide-black px-3">
                    <div class="flex items-center justify-center mt-3">
                    </div>
                    <div class="divide-y divide-black">
                        <div class="flex items-center" v-for="(priceObj, idx) in $page.props.prices" :key="idx">
                            <div class="w-1/6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="max-h-7 w-5 h-5 my-7" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z" />
                                    <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z" />
                                    <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z" />
                                </svg>
                            </div>
                            <div class="flex-grow w-2/6 items-center">
                                <li class="">{{priceObj.amount}} Coin</li>
                            </div>
                            <div class="flex items-center justify-end w-1/2">
                                <button @click="buyTokens(priceObj.amount, priceObj.price)" class="bg-blue-500 w-28 text-center text-white rounded">{{priceObj.price.toLocaleString('id-ID', {style: 'currency', currency: 'IDR'})}}</button>
                            </div>
                        </div>
                    </div>
                    <div class="text-center text-lg font-bold bg-gray-200 h-14 pt-4">Informasi Koin</div>
                </ul>
            </div>
            <ul class="list-disc ml-10 pb-5">
                <li>Koin yang telah di beli tidak dapat di tukarkan kembali</li>
                <li>Episode berbayar hanya bisa di beli dengan koin</li>
                <li>Riwayat pembelian bisa di lihat di halaman riwayat</li>
                <li>Episode yang telah di beli tidak dapat di kembalikan</li>
                <li>Jika kamu membeli koin, kamu setuju dengan kebijakan visi8-webcomic yang berlaku</li>
            </ul>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout.vue'
    export default {
        name: 'coin-store',
        inject: ['swal'],
        components: {
            AppLayout
        },
        data() {
            return {

            }
        },
        methods: {
            buyTokens(tokenAmount, moneyAmount){
                axios.post(route('api.tokens.purchase'), {
                    token_amount: tokenAmount,
                    money_amount: moneyAmount
                })
                .then((response) => {
                    return this.swal.fire({
                        icon: "success",
                        title: "Token purchased!",
                        text: "Comment purchase succesful!",
                    })
                })
                .then((response) => {
                    let lastPage = JSON.parse(localStorage.getItem('lastPage')) || {routeName: 'web.dashboard', params: {}}
                    localStorage.removeItem('lastPage')
                    this.$inertia.visit(route(lastPage.routeName, lastPage.params))
                })
            }
        }
    }
</script>
