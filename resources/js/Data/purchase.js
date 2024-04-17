export const genders = [
    {
        id: 0,
        name: 'Нет',
    },
    {
        id: 1,
        name: 'М',
    },
    {
        id: 2,
        name: 'Ж',
    },
]

export const statuses = {
    pending: {
        title: 'Ожидает обработки',
        theme: 'info',
    },
    processing: {
        title: 'В работе',
        theme: 'info',
    },
    process: {
        title: 'В работе',
        theme: 'info',
    },
    unavailable: {
        title: 'Недоступен',
        theme: 'danger',
    },
    not_enough_w_balance: {
        title: 'Кошелек исчерпан',
        theme: 'danger',
    },
    pickpoint_overloaded: {
        title: 'ПВЗ перегружен',
        theme: 'danger',
    },
    done: {
        title: 'Завершен',
        theme: 'success',
    },
}

export const feedbackPeriods = [
    {
        key: 3,
        name: '3 часа',
    },
    {
        key: 12,
        name: '12 часов',
    },
    {
        key: 24,
        name: '1 день',
    },
    {
        key: 168,
        name: '1 неделя',
    },
    {
        key: 336,
        name: '2 недели',
    },
]

export const complaintTypes = [
    {
        key: 'obscene_language',
        name: 'Нецензурная лексика',
    },
    {
        key: 'dummy_review',
        name: 'Заказной, фиктивный',
    },
    {
        key: 'spam',
        name: 'Спам',
    },
    {
        key: 'bad_product_image',
        name: 'Некорректное фото',
    },
    {
        key: 'not_about_product',
        name: 'Отзыв не о товаре',
    },
]
