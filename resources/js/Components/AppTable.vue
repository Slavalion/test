<template>
    <table class="w-full main-table">
        <thead v-if="$slots.head">
            <slot name="head"></slot>
        </thead>
        <tbody>
            <slot></slot>
        </tbody>
    </table>
</template>
