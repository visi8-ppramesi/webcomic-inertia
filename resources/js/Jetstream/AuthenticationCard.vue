<template>
    <div class="bg-cover bg-no-repeat bg-center" :style="'background-image: linear-gradient(rgba(23,167,105,0.3) 50%, rgb(49 46 129)), url(' + karaBackground.default +');'">
        <div class="flex items-end sm:items-center justify-center h-screen">
            <div class="w-full sm:max-w-md mb-6 px-6 py-4 overflow-hidden sm:rounded-lg">
                <slot />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            karaBackground: require('../../assets/kara_bg.jpg'),
        }
    }
}
</script>
