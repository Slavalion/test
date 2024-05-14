import { ref } from 'vue'

export default {
    sections: [
        {
            title: 'Продвижение',
            items: {
                purchases: {
                    title: 'Выкупы',
                    route: 'purchase.list',
                    icon: 'card',
                },
                deliveries: {
                    title: 'Доставки',
                    route: 'delivery.index',
                    icon: 'package',
                    counter: ref(0),
                },
            },
        },
        {
            title: 'Репутация',
            items: {
                // liker: {
                //     title: 'Лайкер',
                //     route: 'liker',
                //     icon: 'heart',
                // },
                reviews: {
                    title: 'Отзывы',
                    route: 'reviews',
                    icon: 'message',
                    counter: ref(0),
                },
                questions: {
                    title: 'Вопросы',
                    route: 'question',
                    icon: 'question',
                },
                reviewReactions: {
                    title: 'Реакции на отзывы',
                    route: 'review-reactions',
                    icon: 'star',
                },
                reviewComplaints: {
                    title: 'Жалобы на отзывы',
                    route: 'review-complaints',
                    icon: 'dislike',
                },
                addToCart: {
                    title: 'Добавление в корзину',
                    route: 'actions.add-to-cart',
                    icon: 'package',
                },
                actionSearch: {
                    title: 'Поиск по запросам',
                    route: 'actions.search',
                    icon: 'search',
                },
            },
        },
        {
            title: 'Админ',
            role: 1,
            items: {
                dashboard: {
                    title: 'Dashboard',
                    route: 'admin.dashboard',
                    icon: 'setting',
                },
                settings: {
                    title: 'Настройки',
                    route: 'admin.settings',
                    icon: 'setting',
                },
                products: {
                    title: 'Товары',
                    route: 'admin.products',
                    icon: 'card',
                },
                livecargo: {
                    title: 'Livecargo',
                    route: 'admin.livecargo',
                    icon: 'package',
                },
                pickup_zones: {
                    title: 'Зоны забора',
                    route: 'admin.pickup-zones',
                    icon: 'package',
                },
                users: {
                    title: 'Пользователи',
                    route: 'admin.users',
                    icon: 'users',
                },
                messages: {
                    title: 'Рассылка',
                    route: 'admin.message-sender',
                    icon: 'message',
                },
                faqs: {
                    title: 'FAQ',
                    route: 'admin.faqs.index',
                    icon: 'question',
                },
            },
        },
        {
            title: 'Финансы',
            items: [
                {
                    title: 'Заборы',
                    route: 'pick-ups.index',
                    icon: 'package',
                    condition: 'preference:use-livecargo',
                },
                {
                    title: 'Кошельки',
                    route: 'wallets',
                    icon: 'wallet',
                },
                {
                    title: 'Тарифы',
                    route: 'tariffs.index',
                    icon: 'tariffs',
                },
                {
                    title: 'Транзакции',
                    route: 'transactions',
                    icon: 'transactions',
                },
                {
                    title: 'FAQ (вопросы/ответы)',
                    route: 'faq.index',
                    icon: 'question',
                },
                // {
                //     title: 'FAQ',
                //     route: 'users',
                //     icon: 'question',
                // },
                // {
                //     title: 'Тарифы',
                //     route: 'tariffs',
                //     icon: 'tariffs',
                // },
                {
                    title: 'Выход',
                    route: 'logout',
                    icon: 'logout',
                },
            ],
        },
    ],
}
