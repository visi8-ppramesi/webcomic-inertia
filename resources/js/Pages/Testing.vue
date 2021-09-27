<template>
    <input multiple v-for="n in 10" ref="testfile" type="file" @change="fileChange($event, n)" :key="n"/>
    <button @click="go">go</button>
</template>

<script>
export default {
    name: 'testing',
    inject: ['DRM'],
    props: ['testing'],
    data(){
        return {
            img: '',
            files: {},
            count: 0
        }
    },
    methods: {
        go(){
            axios.post(route('api.testing'), {
                files: this.files
            })
            .then((resp) => {
                console.log(resp.data)
            })
        },
        fileChange(e, n){
            var files = e.target.files || e.dataTransfer.files
            // this.testfile[n] = files[0]
            Object.keys(files).forEach((key) => {
                this.toBase64(files[key], this.count)
                this.count += 1
            })
            // this.toBase64(files, n)
        },
        toBase64(fileObj, idx){
            const file = new FileReader()
            file.onload = (e) => {
                this.files[idx] = e.target.result
            }

            file.readAsDataURL(fileObj)
        }
    },
    created(){
    },
    beforeUnmount(){
    }
}
</script>

<style scoped>

</style>
