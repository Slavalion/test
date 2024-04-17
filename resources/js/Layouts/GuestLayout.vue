<script setup></script>

<template>
    <div class="min-h-screen auth-layout">
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
</template>
