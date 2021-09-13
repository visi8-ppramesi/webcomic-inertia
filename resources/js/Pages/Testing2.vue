<template>
    <Link :href="route('web.testing')">heyo</Link>
</template>

<script>
export default {
    name: 'testing',
    inject: ['DRM'],
    props: ['testing'],
    created(){
        console.log(this.DRM.getImages())
    },
    beforeUnmount(){
        this.DRM.destroyBlobImages()
    }
}
</script>

<style scoped>

</style>
