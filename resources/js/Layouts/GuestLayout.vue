<script setup>
import { computed } from 'vue'
import { useWindowSize } from '@vueuse/core'

const { width } = useWindowSize()
const mobileMode = computed(() => {
    return width.value <= 390
})
</script>

<template>
    <div :class="mobileMode ? 'min-h-screen auth-layout-mobile' : 'min-h-screen auth-layout'">
        <div :class="mobileMode ? 'auth-layout-mobile__bg' : 'auth-layout__bg'"></div>

        <div :class="mobileMode ? 'auth-layout-mobile__logo' : 'auth-layout__logo'">
            <img src="/images/LogoWhite.svg" alt="MPB.top" height="36" />
        </div>

        <div ref="elg" :class="mobileMode ? 'auth-panel-mobile' : 'auth-panel'">
            <div class="auth-panel-mobile__head" v-if="mobileMode">
                <div class="auth-panel-mobile__head-title">
                    <slot name="title"></slot>
                </div>
                <div class="auth-panel-mobile__head-caption">
                    <slot name="caption"></slot>
                </div>
            </div>

            <div class="auth-panel__head" v-else>
                <div class="auth-panel__head-title">
                    <slot name="title"></slot>
                </div>
                <div class="auth-panel__head-caption">
                    <slot name="caption"></slot>
                </div>
            </div>

            <div :class="mobileMode ? 'auth-panel-mobile__body' : 'auth-panel__body'">
                <slot></slot>
            </div>
            <div :class="mobileMode ? 'auth-panel-mobile__footer' : 'auth-panel__footer'">
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
@import './../../scss/vars';
@import './../../scss/mixins';

.auth-panel {
    width: 480px;
    border-radius: 25px;
    box-shadow: -1px 3px 8px 0px rgba(89, 128, 206, 0.05);
    background-color: $functionalWhite;

    &__head {
        padding: 36px;
        border-bottom: 1px solid $neuralNeural4;

        &-title {
            margin-bottom: 12px;
            color: $neuralNeural10;
            user-select: none;
            @include header(2);
        }

        &-caption {
            color: $neuralNeural8;
            user-select: none;
            @include paragraph(2);
        }
    }

    &__body {
        padding: 36px;
    }

    &__footer {
        padding: 36px;
        border-top: 1px solid $neuralNeural4;
    }
}

@media (min-width: 390px) and (max-width: 500px) {
    .auth-panel {
        width: calc(100vw - 20px);

        &__head {
            padding: 7.2vw;

            &-title {
                margin-bottom: 2.4vw;
            }
        }

        &__body {
            padding: 7.2vw;
        }

        &__footer {
            padding: 7.2vw;
        }
    }
}

.auth-panel-mobile {
    width: 100%;
    border-radius: 6.15vw 6.15vw 0 0;
    background-color: $functionalWhite;
    overflow-y: auto;

    flex-grow: 1;

    &__head {
        min-height: 28.2vw;
        padding: 5.128vw;
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 3.07vw;
        border-bottom: 1px solid $neuralNeural4;

        &-title {
            width: 87vw;
            height: 10.2564vw;
            color: $neuralNeural10;
            user-select: none;
            @include mobileheader2;
        }

        &-caption {
            width: 87vw;
            color: $neuralNeural8;
            user-select: none;
            @include mobileparagraph1;
        }
    }

    &__body {
        padding: 5.128vw;
        gap: 5.128vw;
    }

    &__footer {
        padding: 5.128vw;
        border-top: 1px solid $neuralNeural4;
    }
}
</style>
