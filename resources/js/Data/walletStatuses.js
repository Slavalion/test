export default {
    0: {
        code: 'PENDING',
        title: 'В процессе авторизации',
        labelTheme: 'normal',
    },
    1: {
        code: 'READY',
        title: 'Готов к работе',
        labelTheme: 'normal',
    },
    2: {
        code: 'LIMIT_REACHED',
        title: 'Лимит исчерпан',
        labelTheme: 'warning',
    },
    3: {
        value: 'LOGED_OUT',
        title: 'Разлогин',
        labelTheme: 'warning',
    },
    4: {
        value: 'NEED_VERIF',
        title: 'Нужен именной кошелек',
        labelTheme: 'warning',
    },
    isUpdateAvailable: (statusCode) => {
        return ['READY', 'LIMIT_REACHED'].includes(statusCode)
    },
}
