<script setup>
import device from 'vue3-device-detector'
import { useWindowSize } from '@vueuse/core'
const { width } = useWindowSize()
</script>

<template>
    <div class="min-h-screen auth-layout" v-if="device().isDesktop && width > 390">
        <div class="auth-layout__bg"></div>

        <div class="auth-layout__logo">
            <img src="/images/LogoWhite.svg" alt="MPB.top" height="36" />
        </div>

        <div class="auth-panel">
            <div class="auth-panel__head">
                <div class="auth-panel__head-title">
                    <slot name="title"></slot>
                </div>
                <div class="auth-panel__head-caption">
                    <slot name="caption"></slot>
                </div>
            </div>
            <div class="auth-panel__body">
                <slot></slot>
            </div>
            <div class="auth-panel__footer">
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
    <div v-else class="min-h-screen auth-layout-mobile">
        <div class="auth-layout-mobile__bg"></div>
        <div class="auth-layout-mobile__logo">
            <img src="/images/LogoWhite.svg" alt="MPB.top" height="36" />
        </div>

        <div class="auth-panel-mobile">
            <div class="auth-panel-mobile__head">
                <div class="auth-panel-mobile__head-title">
                    <slot name="title"></slot>
                </div>
                <div class="auth-panel-mobile__head-caption">
                    <slot name="caption"></slot>
                </div>
            </div>
            <div class="auth-panel-mobile__body">
                <slot></slot>
            </div>
            <div class="auth-panel-mobile__footer">
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>
s
