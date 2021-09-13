<template>
    <Link :href="route('web.testing2')">heyo</Link>
    <img :src="img" />
</template>

<script>
export default {
    name: 'testing',
    inject: ['DRM'],
    props: ['testing'],
    data(){
        return {
            img: ''
        }
    },
    created(){
        let r = (Math.random() + 1).toString(36).substring(7);
        this.DRM.createImageBlob(this.testing.image_url, r)
        .then((blob) => {
            console.log(blob)
            this.img = blob
            this.DRM.revokeImageBlob(blob)
        })
    },
    beforeUnmount(){
        this.DRM.destroyBlobImages()
    }
}
</script>

<style scoped>

</style>
