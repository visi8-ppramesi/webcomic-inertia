<template>
    <jet-authentication-card>
        <jet-validation-errors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <jet-input placeholder="Email" id="email" type="email" class="shadow appearance-none border rounded-full w-full py-2 px-3 text-grey-darker" v-model="form.email" required autofocus />
            </div>

            <div class="mt-4">
                <jet-input placeholder="Password" id="password" type="password" class="shadow appearance-none border border-red rounded-full w-full py-2 px-3 text-grey-darker mb-3" v-model="form.password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <jet-checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-white">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')" class="text-white underline text-sm text-gray-600 hover:text-gray-900">
                    Forgot your password?
                </Link>

                <jet-button class="bg-green-400 hover:bg-blue-dark text-white font-bold ml-4 rounded-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </jet-button>
            </div>
        </form>
    </jet-authentication-card>

    <!-- <div class="bg-cover bg-no-repeat bg-center" :style="'background-image: linear-gradient(rgba(23,167,105,0.3) 50%, rgb(49 46 129)), url(' + karaBackground.default +');'">
        <div class="flex items-end h-screen">
            <div class="w-full p-5">
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <input name="email" for="email" class="shadow appearance-none border rounded-full w-full py-2 px-3 text-grey-darker" v-model="form.email" id="email" type="text" placeholder="Email">
                    </div>
                    <div class="pass-form">
                        <input name="password" for="password" class="shadow appearance-none border border-red rounded-full w-full py-2 px-3 text-grey-darker mb-3" v-model="form.password" id="password" type="password" placeholder="Password">
                    </div>
                    <div class="text-sm text-center text-white mb-10">
                        <router-link to="#">Forgot Password?</router-link>
                    </div>
                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>
                    <div class="flex items-center justify-between">
                        <jet-button class="bg-green-400 w-full hover:bg-blue-dark text-white font-bold py-2 px-4 rounded-full" type="button">
                            LOG IN
                        </jet-button>
                    </div>
                    <div class="text-m text-center mt-6 text-white">
                        <p>Dont Have Account Yet? <a :href="route('web.register')">Sign Up</a></p>
                    </div>
                    <div class="flex flex-row items-center justify-center pt-5">
                        <img class="w-10" :src="facebookIcon.default" />
                        <img class="w-10" :src="instagramIcon.default" />
                        <img class="w-10" :src="twitterIcon.default" />
                    </div>
                </form>
            </div>
        </div>
    </div> -->
</template>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetCheckbox from '@/Jetstream/Checkbox.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default {
        components: {
            Head,
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors,
            Link,
        },

        props: {
            canResetPassword: Boolean,
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                }),
                facebookIcon: require('../../../icons/facebook.png'),
                instagramIcon: require('../../../icons/instagram.png'),
                twitterIcon: require('../../../icons/twitter.png'),
                karaBackground: require('../../../assets/kara_bg.jpg'),
            }
        },

        methods: {
            submit() {
                axios.get('/sanctum/csrf-cookie').then(response => {
                    this.form
                        .transform(data => ({
                            ... data,
                            remember: this.form.remember ? 'on' : ''
                        }))
                        .post(this.route('login'), {
                            onFinish: () => this.form.reset('password'),
                        })
                })
            }
        }
    }
</script>
