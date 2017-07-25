<template>
    <li aria-haspopup="true" class="dropdown-submenu">
        <a :href="url" class="nav-link nav-toggle" v-text="title"></a>
        <ul class="dropdown-menu">
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