<template>
    <div class="text-gray-50 bg-gray-800 mt-0 h-auto w-full z-20 top-0 font-sans">
        <nav class="bg-gray-800">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="relative flex items-center justify-between h-16">
                    <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                        <!-- Mobile menu button-->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                        <div @click="$inertia.visit(route('web.dashboard'))" class="flex-shrink-0 flex items-center">
                            <img class="block lg:hidden h-16 w-auto" :src="visi8Icon.default" alt="Workflow">
                            <img class="hidden lg:block h-8 w-auto" :src="visi8Icon.default" alt="Workflow">
                        </div>
                        <div class="hidden sm:block sm:ml-6">
                            <div class="flex space-x-4">
                                <!-- <router-link @click="mobileMenuOpen = false" :to="{name: 'dashboard'}" class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium" aria-current="page">Dashboard</router-link>
                                <router-link :to="{}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Team</router-link>
                                <router-link :to="{}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Projects</router-link>
                                <router-link :to="{}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Calendar</router-link> -->
                            </div>
                        </div>
                    </div>
                    <div v-if="isLoggedIn" class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                        <!-- Profile dropdown -->
                        <svg @click="goToSearch" xmlns="http://www.w3.org/2000/svg" class="ml-2 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <!-- <span class="badge" v-if="cartCount > 0">{{cartCount}}</span>
                        <svg @click="goToPayment" xmlns="http://www.w3.org/2000/svg" class="ml-2 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg> -->
                        <div class="ml-2 relative">
                            <div>
                                <button @click="profileMenuOpen = !profileMenuOpen" type="button" class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full" :src="$page.props.user.profile_photo_url" alt="">
                                </button>
                            </div>
                            <div v-if="profileMenuOpen" class="z-50 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                <!-- Active: "bg-gray-100", Not Active: "" -->
                                <Link :href="route('profile.show')" class="block px-4 py-2 text-sm text-gray-700">My Account</Link>
                                <div @click="$inertia.post(route('logout'))" class="block px-4 py-2 text-sm text-gray-700">Logout</div>
                                <!-- <router-link :to="{name: 'logout'}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Sign out</router-link> -->
                                <!-- <router-link :to="{name: 'testing'}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Testing</router-link> -->
                            </div>
                        </div>
                    </div>
                    <div v-else class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                        <svg @click="goToSearch" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <div class="ml-3 relative">
                            <div>
                                <button @click="profileMenuOpen = !profileMenuOpen" type="button" class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </button>
                            </div>
                            <div v-if="profileMenuOpen" class="z-50 text-black flex flex-col origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                <!-- Active: "bg-gray-100", Not Active: "" -->
                                <Link :href="route('login')" class="block px-4 py-2 text-sm text-gray-700">Login</Link>
                                <Link :href="route('register')" class="block px-4 py-2 text-sm text-gray-700">Register</Link>
                            </div>
                        </div>
                        <!-- <router-link :to="{name: 'login'}">Login</router-link> -->
                        <!-- <Link :href="route('login')">Login</Link> -->
                        <!-- <Link :href="route('register')">Register</Link> -->
                    </div>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state. -->
            <div class="sm:hidden bg-gray-800 absolute w-full" id="mobile-menu" v-if="mobileMenuOpen">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <Link :href="route('web.dashboard')" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Dashboard</Link>
                    <Link :href="route('web.mycomics')" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">My Comics</Link>
                    <Link :href="route('web.mytransactions')" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">My Transactions</Link>
                    <Link :href="route('web.purchasetokens')" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Purchase Tokens</Link>
                    <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                    <!-- <router-link @click.native="mobileMenuOpen = false" :to="{name: 'dashboard'}" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Dashboard</router-link>
                    <router-link :to="{}" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">My Comics</router-link>
                    <router-link :to="{}" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">My Account</router-link> -->
                </div>
            </div>
        </nav>
        <div class="flex flex-col md:flex-row">
            <div class="max-w-full min-h-screen main-content flex-1 bg-gradient-to-t from-purple-800 to-indigo-900 md:pb-5 h-auto text-black">
                <slot :key="route().current()"></slot>
            </div>
        </div>
        <div class="w-100 bg-gray-800 divide-y text-center h-full">
            <div class="h-12 py-2">
                <Link :href="route('web.aboutus')">About Us</Link>
            </div>
            <div class="h-12 py-2">
                My Account
            </div>
            <div class="h-12 py-2">
                <Link :href="route('web.privacypolicy')">Privacy Policy</Link>
            </div>
            <div class="h-12 py-2">
                FAQ
            </div>
            <div class="py-2">
                Follow Us On
                <div class="flex w-full flex flex-row items-center justify-center mt-2">
                    <a v-for="(soc, idx) in socials" :key="'soc-' + idx" :href="soc.link">
                        <img class="w-12" :src="soc.icon" />
                    </a>
                    <!-- <img class="w-12" :src="facebookIcon.default" />
                    <img class="w-12" :src="instagramIcon.default" />
                    <img class="w-12" :src="twitterIcon.default" /> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { usePage } from '@inertiajs/inertia-vue3'
export default {
    name: 'app-layout',
    inject: ["mq"],
    methods: {
        goToSearch(){
            this.$inertia.visit(route('web.search'))
            // this.$router.push({name: 'paymentShow'})
        },
        goToPayment(){
            this.$inertia.visit(route('web.payment'))
            // this.$router.push({name: 'paymentShow'})
        },
        countCartItems(){
            let count = 0
            let cartObj = JSON.parse(localStorage.getItem('cart') || '{}')
            Object.keys(cartObj).forEach((key) => {
                count += cartObj[key].length
            })
            this.cartCount = count
        }
    },
    created() {
        this.isLoggedIn = !!usePage().props.value.user
        this.countCartItems()
        this.emitter.on('cartAddItem', (e) => {
            this.countCartItems()
        })
        axios.get(route('api.settings', 'site.social_media_links'))
            .then((resp) => {
                this.socials = Object.keys(resp.data).map((socname) => {
                    const icon = socname in this.icons ? this.icons[socname] : this.icons.visi8
                    return {
                        name: socname,
                        link: resp.data[socname],
                        icon: icon.default
                    }
                })
            })
    },
    data() {
        return {
            cartCount: 0,
            mobileMenuOpen: false,
            profileMenuOpen: false,
            isLoggedIn: false,
            icons: {
                facebook: require('../../icons/facebook.png'),
                instagram: require('../../icons/instagram.png'),
                twitter: require('../../icons/twitter.png'),
                visi8: require('../../assets/visi8_logo.png')
            },
            visi8Icon: require('../../assets/visi8_logo.png'),
            socials: [],
        }
    }
}
</script>
<style>
#mobile-menu{
    z-index: 900;
}
.slide-enter-active {
   -moz-transition-duration: 0.3s;
   -webkit-transition-duration: 0.3s;
   -o-transition-duration: 0.3s;
   transition-duration: 0.3s;
   -moz-transition-timing-function: ease-in;
   -webkit-transition-timing-function: ease-in;
   -o-transition-timing-function: ease-in;
   transition-timing-function: ease-in;
}

.slide-leave-active {
   -moz-transition-duration: 0.3s;
   -webkit-transition-duration: 0.3s;
   -o-transition-duration: 0.3s;
   transition-duration: 0.3s;
   -moz-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
   -webkit-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
   -o-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
   transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
}

.slide-enter-to, .slide-leave {
   max-height: 100px;
   overflow: hidden;
}

.slide-enter, .slide-leave-to {
   overflow: hidden;
   max-height: 0;
}
.badge{
    position: relative;
    top: -13px;
    right: -34px;
    padding: 0px 5px;
    border-radius: 50%;
    background: red;
    font-size: 12px;
    color: white;
    pointer-events: none;
}
</style>
