import { ref } from 'vue'
import { useToast } from 'vue-toastification'

export function useAxios() {
    const toast = useToast()
    const loading = ref(false)

    const sendRequest = (url, config) => {
        loading.value = true

        return new Promise((resolve, reject) => {
            axios(url, config)
                .then((response) => {
                    loading.value = false

                    resolve(response)
                })
                .catch((error) => {
                    loading.value = false

                    if (error.response.status == 422) {
                        toast.error(error.response.data.message)
                    }

                    reject(error)
                })
        })
    }

    const get = (url, data) => {
        return sendRequest(url, {
            method: 'get',
            params: data,
        })
    }

    const post = (url, data) => {
        return sendRequest(url, {
            method: 'post',
            data: data,
        })
    }

    return {
        loading,
        get,
        post,
    }
}
