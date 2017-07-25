<template>
    <li aria-haspopup="true">
        <a :href="url" class="nav-link" v-text="title"></a>
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