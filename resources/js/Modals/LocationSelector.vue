<script setup>
import Modal from '@/Components/Modal.vue'
import { onMounted } from 'vue'
import dayjs from 'dayjs'

import { useAxios } from '@/Composables/useAxios'

import { locationSelector } from '@/modals.js'
import pickpoints from '@/pickpoints'

const props = defineProps({
    mapContainer: {
        type: String,
        default: 'map',
    },
})

const api = useAxios()

const settings = {
    apiKey: '5858e215-0f0c-41e2-a387-9d0943f3dee2',
}

const open = () => {
    // nextTick(() => balanceInput.value.focus())
}

const emit = defineEmits(['selectPickpoint'])

onMounted(async () => {
    if (pickpoints.items.length == 0) {
        await api.get('/storage/pickpoints.json?v=' + dayjs().format('YYYYMD')).then((resp) => {
            pickpoints.items = resp.data
        })
    }

    ymaps.ready(() => {
        const myMap = new ymaps.Map(props.mapContainer, {
            center: [55.76, 37.64],
            zoom: 7,
        })

        const objectManager = new ymaps.ObjectManager({
            // Чтобы метки начали кластеризоваться, выставляем опцию.
            clusterize: true,
            // ObjectManager принимает те же опции, что и кластеризатор.
            gridSize: 32,
            // clusterDisableClickZoom: true,
        })

        // Создание макета балуна на основе Twitter Bootstrap.

        // Создание вложенного макета содержимого балуна.
        const MyBalloonLayout = ymaps.templateLayoutFactory.createClass(
            '<div class="map-balloon" id="map-ballon">' +
                '<div class="map-balloon__header">Пункт выдачи Wildberries</div>' +
                '<div class="map-balloon__body">' +
                '$[[options.contentLayout observeSize minWidth=212 maxWidth=212 maxHeight=350]]' +
                '</div>' +
                '</div>',
            {
                build: function () {
                    this.constructor.superclass.build.call(this)

                    this._element = document.getElementById('map-ballon')

                    var blnBtn = document
                        .getElementById('baloonBtn')
                        .addEventListener('click', (event) => {
                            chooseAddress(this._data.object)
                        })

                    this.applyElementOffset()
                },

                clear: function () {
                    this.constructor.superclass.clear.call(this)
                },

                onSublayoutSizeChange: function () {
                    MyBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments)

                    if (!this._isElement(this._element)) {
                        return
                    }

                    this.applyElementOffset()

                    this.events.fire('shapechange')
                },

                applyElementOffset: function () {
                    this._element.style.left = -this._element.offsetWidth / 2 + 'px'
                    this._element.style.top = -(this._element.offsetHeight + 42) + 'px'
                },

                getShape: function () {
                    if (!this._isElement(this._element)) {
                        return MyBalloonLayout.superclass.getShape.call(this)
                    }

                    var position = {
                        left: parseInt(this._element.style.left),
                        top: parseInt(this._element.style.top),
                    }

                    return new ymaps.shape.Rectangle(
                        new ymaps.geometry.pixel.Rectangle([
                            [position.left, position.top],
                            [
                                position.left + this._element.offsetWidth,
                                position.top + this._element.offsetHeight + 0,
                            ],
                        ])
                    )
                },

                _isElement: function (element) {
                    return element && element.querySelector('#baloonBtn')
                },
            }
        )
        // Создание вложенного макета содержимого балуна.
        const MyBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
            '<div class="map-balloon__body-wrapper">' +
                '<div class="map-balloon__subhead">Режим работы:</div>' +
                '<div class="map-balloon__value">$[properties.balloonContentWorkTime]</div>' +
                '<div class="map-balloon__subhead">Адрес:</div>' +
                '<div class="map-balloon__value">$[properties.balloonContentAddress]</div>' +
                '<button class="btn btn_size-md btn_fillwidth" id="baloonBtn" >Выбрать<button>' +
                '</div>'
        )

        ymaps.option.presetStorage.add('mark#icon', {
            iconLayout: 'default#image',
            iconImageHref: '/images/map-marker.svg',
            iconImageSize: [42, 50],
            iconImageOffset: [-21, -50],
            hideIconOnBalloonOpen: false,
            balloonShadow: false,
            balloonLayout: MyBalloonLayout,
            balloonContentLayout: MyBalloonContentLayout,
        })

        objectManager.add(pickpoints.items)

        objectManager.objects.options.set({
            preset: 'mark#icon',
        })

        objectManager.clusters.options.set({
            preset: 'mark#icon',
            // preset: "islands#blueClusterIcons",
            iconImageHref: '/images/map-marker.svg',
            iconColor: '#1665FF',
        })

        myMap.geoObjects.add(objectManager)
    })
})

const chooseAddress = (pickpoint) => {
    emit('selectPickpoint', pickpoint.properties.balloonContentAddress)
    // locationSelector.object.address = pickpoint.properties.balloonContentAddress
    locationSelector.close()
}
</script>
<template>
    <Modal
        :show="locationSelector.show"
        :hide-footer="true"
        @close="locationSelector.close()"
        @open="open"
        wrapper-class="modal__wrapper_size-lg"
        body-class="modal__body_nopadding"
    >
        <template #header> Пункты доставки </template>
        <!--
        <template #caption>
            <div style="margin-bottom: -12px">Выбор пвз</div>
        </template> -->

        <div :id="mapContainer" class="map-container"></div>
    </Modal>
</template>
