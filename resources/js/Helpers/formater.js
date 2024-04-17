export const currencyFormater = {
    format(value) {
        const formatter = new Intl.NumberFormat('ru-RU', {
            style: 'currency',
            currency: 'RUB',
            // maximumFractionDigits: 3,
        })

        return formatter.format(value).replace(',00', '')
    },
    animateValue(object, start, end, duration) {
        let startTimestamp = null

        const step = (timestamp) => {
            if (!startTimestamp) {
                startTimestamp = timestamp
            }

            const progress = Math.min((timestamp - startTimestamp) / duration, 1)

            object.value = Math.floor(progress * (end - start) + start)

            if (progress < 1) {
                window.requestAnimationFrame(step)
            }
        }

        window.requestAnimationFrame(step)
    },
}
