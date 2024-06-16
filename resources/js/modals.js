import { reactive } from 'vue'
import { useLocalStorage } from '@vueuse/core'

export const fillUpBalance = reactive({
    show: JSON.parse(localStorage.getItem('fillUpBalance')) === true,
    close() {
        this.show = false
        this.storeValue()
    },
    open() {
        this.show = true
        this.storeValue()
    },
    storeValue() {
        localStorage.setItem('fillUpBalance', this.show)
    },
})

export const purchaseSlide = reactive({
    show: JSON.parse(localStorage.getItem('purchaseSlide')) === true,
    close() {
        this.show = false
        this.storeValue()
    },
    open() {
        this.show = true
        this.storeValue()
    },
    storeValue() {
        localStorage.setItem('purchaseSlide', this.show)
    },
})

export const reviewReactionSlide = reactive({
    show: JSON.parse(localStorage.getItem('reviewReactionSlide')) === true,
    close() {
        this.show = false
        this.storeValue()
    },
    open() {
        this.show = true
        this.storeValue()
    },
    storeValue() {
        localStorage.setItem('reviewReactionSlide', this.show)
    },
})

export const reviewComplaintSlide = reactive({
    show: JSON.parse(localStorage.getItem('reviewComplaintSlide')) === true,
    close() {
        this.show = false
        this.storeValue()
    },
    open() {
        this.show = true
        this.storeValue()
    },
    storeValue() {
        localStorage.setItem('reviewComplaintSlide', this.show)
    },
})

export const actionCartModal = reactive({
    show: JSON.parse(localStorage.getItem('actionCartModal')) === true,
    close() {
        this.show = false
        this.storeValue()
    },
    open() {
        this.show = true
        this.storeValue()
    },
    storeValue() {
        localStorage.setItem('actionCartModal', this.show)
    },
})

export const actionSearchModal = reactive({
    show: JSON.parse(localStorage.getItem('actionSearchModal')) === true,
    close() {
        this.show = false
        this.storeValue()
    },
    open() {
        this.show = true
        this.storeValue()
    },
    storeValue() {
        localStorage.setItem('actionSearchModal', this.show)
    },
})

export const purchaseGenerator = reactive({
    show: JSON.parse(localStorage.getItem('purchaseGenerator')) === true,
    close() {
        this.show = false
        this.storeValue()
    },
    open() {
        this.show = true
        this.storeValue()
    },
    storeValue() {
        localStorage.setItem('purchaseGenerator', this.show)
    },
})

export const purchaseImport = reactive({
    show: JSON.parse(localStorage.getItem('purchaseImport')) === true,
    close() {
        this.show = false
        this.storeValue()
    },
    open() {
        this.show = true
        this.storeValue()
    },
    storeValue() {
        localStorage.setItem('purchaseImport', this.show)
    },
})

export const pickUpImport = reactive({
    show: JSON.parse(localStorage.getItem('pickUpImport')) === true,
    close() {
        this.show = false
        this.storeValue()
    },
    open() {
        this.show = true
        this.storeValue()
    },
    storeValue() {
        localStorage.setItem('pickUpImport', this.show)
    },
})

export const locationSelector = reactive({
    show: JSON.parse(localStorage.getItem('locationSelector')) === true,
    close() {
        this.show = false
        this.storeValue()
    },
    open() {
        this.show = true
        this.storeValue()
    },
    storeValue() {
        localStorage.setItem('locationSelector', this.show)
    },
})

export const updateDeliveryData = reactive({
    // show: JSON.parse(localStorage.getItem('updateDeliveryData')) === true,
    show: false,
    close() {
        this.show = false
        // this.storeValue()
    },
    open() {
        this.show = true
        // this.storeValue()
    },
    storeValue() {
        localStorage.setItem('updateDeliveryData', this.show)
    },
})

export const addWallet = reactive({
    show: useLocalStorage('addWallet', false),
    walletCode: localStorage.getItem('addWallet_walletCode'),
    close() {
        this.show = false
        this.storeValue()
    },
    open(walletCode) {
        this.walletCode = walletCode
        this.show = true
        this.storeValue()
    },
    storeValue() {
        // localStorage.setItem('addWallet', this.show)
        localStorage.setItem('addWallet_walletCode', this.walletCode)
    },
})

export const addReview = reactive({
    // show: JSON.parse(localStorage.getItem('updateDeliveryData')) === true,
    show: false,
    close() {
        this.show = false
        // this.storeValue()
    },
    open() {
        this.show = true
        // this.storeValue()
    },
    storeValue() {
        localStorage.setItem('updateDeliveryData', this.show)
    },
})

export const profileInformation = reactive({
    show: false,
    close() {
        this.show = false
    },
    open() {
        this.show = true
    },
})

export const profilePassword = reactive({
    show: false,
    close() {
        this.show = false
    },
    open() {
        this.show = true
    },
})

export const profileTelegramPayment = reactive({
    show: false,
    close() {
        this.show = false
    },
    open() {
        this.show = true
    },
})

export const profileDelete = reactive({
    show: false,
    close() {
        this.show = false
    },
    open() {
        this.show = true
    },
})

export const addQuestion = reactive({
    show: false,
    // walletCode: localStorage.getItem('addWallet_walletCode'),
    close() {
        this.show = false
        // this.storeValue()
    },
    open(id) {
        // this.walletCode = walletCode
        this.show = true
        // this.storeValue()
    },
    storeValue() {
        localStorage.setItem('addQuestion', this.show)
        // localStorage.setItem('addWallet_walletCode', this.walletCode)
    },
})
