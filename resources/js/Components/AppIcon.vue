<script>
import IconFileDownload from '@/Icons/FileDownload.vue'
import IconLike from '@/Icons/Like.vue'
import IconLoader from '@/Icons/Loader.vue'
import IconAlertOctagon from '../Icons/AlertOctagon.vue'
import IconAlertTriangle from '../Icons/AlertTriangle.vue'
import IconArrowUp from '../Icons/ArrowUp.vue'
import IconBluePurchase from '../Icons/BluePurchase.vue'
import IconBlueLike from '../Icons/BlueLike.vue'
import IconBlueBox from '../Icons/BlueBox.vue'
import IconBlueQR from '../Icons/BlueQR.vue'
import IconBlueReview from '../Icons/BlueReview.vue'
import IconBlueQuestion from '../Icons/BlueQuestion.vue'
import IconBlueStar from '../Icons/BlueStar.vue'
import IconBlueUsers from '../Icons/BlueUsers.vue'
import IconGreenUsers from '../Icons/GreenUsers.vue'
import IconRedUsers from '../Icons/RedUsers.vue'
import IconBlueBack from '../Icons/BlueBack.vue'
import IconRedDeleted from '../Icons/RedDeleted.vue'
import IconRedPurchase from '../Icons/RedPurchase.vue'
import IconRedLike from '../Icons/RedLike.vue'
import IconRedReview from '../Icons/RedReview.vue'
import IconRedQuestion from '../Icons/RedQuestion.vue'
import IconRedStar from '../Icons/RedStar.vue'
import IconCalendar from '../Icons/Calendar.vue'
import IconCard from '../Icons/Card.vue'
import IconCheckCircle from '../Icons/CheckCircle.vue'
import IconChevronDown from '../Icons/ChevronDown.vue'
import IconChevronsLeft from '../Icons/ChevronsLeft.vue'
import IconChevronsRight from '../Icons/ChevronsRight.vue'
import IconClose from '../Icons/Close.vue'
import IconCopy from '../Icons/Copy.vue'
import IconDelete from '../Icons/Delete.vue'
import IconDislike from '../Icons/Dislike.vue'
import IconEdit from '../Icons/Edit.vue'
import IconExcelFile from '../Icons/ExcelFile.vue'
import IconEye from '../Icons/Eye.vue'
import IconEyeOff from '../Icons/EyeOff.vue'
import IconHeart from '../Icons/Heart.vue'
import IconInfo from '../Icons/Info.vue'
import IconImage from '../Icons/Image.vue'
import IconLayers from '../Icons/Layers.vue'
import IconLock from '../Icons/Lock.vue'
import IconLogout from '../Icons/Logout.vue'
import IconMessage from '../Icons/Message.vue'
import IconMinus from '../Icons/Minus.vue'
import IconMenu from '../Icons/Menu.vue'
import IconPackage from '../Icons/Package.vue'
import IconPlus from '../Icons/Plus.vue'
import IconPlusCircle from '../Icons/PlusCircle.vue'
import IconQr from '../Icons/Qr.vue'
import IconQuestion from '../Icons/Question.vue'
import IconRefresh from '../Icons/Refresh.vue'
import IconRub from '../Icons/Rub.vue'
import IconSearch from '../Icons/Search.vue'
import IconSettings from '../Icons/Settings.vue'
import IconShare from '../Icons/Share.vue'
import IconShield from '../Icons/Shield.vue'
import IconSort from '../Icons/Sort.vue'
import IconStar from '../Icons/Star.vue'
import IconTelegramm from '../Icons/Telegramm.vue'
import IconTimer from '../Icons/Timer.vue'
import IconTariffs from '../Icons/Tariffs.vue'
import IconTransactions from '../Icons/Transactions.vue'
import IconUser from '../Icons/User.vue'
import IconUsers from '../Icons/Users.vue'
import IconYellowEdit from '../Icons/YellowEdit.vue'
import IconWallet from '../Icons/Wallet.vue'

const icons = {
    'alert-triangle': IconAlertTriangle,
    'alert-octagon': IconAlertOctagon,
    'arrow-up': IconArrowUp,
    'blue-purchase': IconBluePurchase,
    'blue-like': IconBlueLike,
    'blue-review': IconBlueReview,
    'blue-question': IconBlueQuestion,
    'blue-star': IconBlueStar,
    'blue-qr': IconBlueQR,
    'blue-box': IconBlueBox,
    'blue-users': IconBlueUsers,
    'green-users': IconGreenUsers,
    'red-users': IconRedUsers,
    'blue-back': IconBlueBack,
    'red-purchase': IconRedPurchase,
    'red-like': IconRedLike,
    'red-review': IconRedReview,
    'red-question': IconRedQuestion,
    'red-star': IconRedStar,
    'red-deleted': IconRedDeleted,
    calendar: IconCalendar,
    card: IconCard,
    'check-circle': IconCheckCircle,
    'chevron-down': IconChevronDown,
    'chevrons-left': IconChevronsLeft,
    'chevrons-right': IconChevronsRight,
    close: IconClose,
    copy: IconCopy,
    dislike: IconDislike,
    delete: IconDelete,
    edit: IconEdit,
    'excel-file': IconExcelFile,
    eye: IconEye,
    eyeoff: IconEyeOff,
    'file-download': IconFileDownload,
    heart: IconHeart,
    info: IconInfo,
    img: IconImage,
    layers: IconLayers,
    like: IconLike,
    loader: IconLoader,
    lock: IconLock,
    logout: IconLogout,
    message: IconMessage,
    minus: IconMinus,
    menu: IconMenu,
    package: IconPackage,
    plus: IconPlus,
    'plus-circle': IconPlusCircle,
    qr: IconQr,
    question: IconQuestion,
    refresh: IconRefresh,
    rub: IconRub,
    search: IconSearch,
    setting: IconSettings,
    share: IconShare,
    shield: IconShield,
    sort: IconSort,
    star: IconStar,
    telegramm: IconTelegramm,
    timer: IconTimer,
    tariffs: IconTariffs,
    transactions: IconTransactions,
    user: IconUser,
    users: IconUsers,
    'yellow-edit': IconYellowEdit,
    wallet: IconWallet,
}
export default {}
</script>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    icon: {
        type: String,
        required: true,
        validator(value) {
            return Object.prototype.hasOwnProperty.call(icons, value)
        },
    },
})

const iconComponent = computed(() => {
    return icons[props.icon]
})
</script>
<template>
    <component :is="iconComponent"></component>
</template>
