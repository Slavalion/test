import { onMounted } from 'vue'

export function useTelegramAuth(parentRef, authUrl) {
    onMounted(() => {
        const script = document.createElement('script')

        script.async = true
        script.src = 'https://telegram.org/js/telegram-widget.js'

        script.setAttribute('data-size', 'large')
        script.setAttribute('data-userpic', 'true')
        script.setAttribute('data-telegram-login', import.meta.env.VITE_TELEGRAM_BOT_NAME)
        script.setAttribute('data-request-access', 'write')
        script.setAttribute('data-auth-url', authUrl)
        script.setAttribute('data-referral', 123321)

        parentRef.value.appendChild(script)
    })
}
