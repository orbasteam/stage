<template>
    <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
        <a :href="url" v-text="title">
            <span class="arrow"></span>
        </a>
        <ul class="dropdown-menu pull-left">
            <slot></slot>
        </ul>
    </li>
</template>

<script>
    export default {
        props: {
            title: String,
            url: {
                default: 'javascript:;',
                type: String
            }
        }
    }
</script>