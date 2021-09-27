let images = {}

export default{
    data: null,
    createImageBlob(url, identifier){
        return axios.get(url, { responseType: 'blob' })
        .then((resp) => {
            this.data = resp.data
            images[identifier] = URL.createObjectURL(this.data)
            return images[identifier]
        })
    },
    revokeImageBlob(url){
        setTimeout(() => {
            this.data = null
            delete images[Object.keys(images).find(key => images[key] === url)]
            URL.revokeObjectURL(url)
        }, 0)
    },
    getBlob(identifier){
        return images[identifier]
    },
    getImages(){
        return images
    },
    destroyBlobImages(){
        images = {}
    }
}
