<template>
    <div class="empty-state">
        <div class="empty-state__image">
            <slot name="image"></slot>
        </div>
        <div class="empty-state__info">
            <div class="empty-state__title">
                <slot name="title"></slot>
            </div>
            <slot></slot>
        </div>
    </div>
</template>
