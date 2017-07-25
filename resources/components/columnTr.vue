<template>
    <tr>
        <td v-text="columnName"></td>
        <td>
            <b-input v-model="element.name" @input="update(columnName, element)" :loading="loading"></b-input>
        </td>
        <td v-text="element.type"></td>
        <td v-text="element.length"></td>
    </tr>
</template>

<script>
    import _ from 'lodash';
    
    export default {
        props: ['columnName', 'element'],
        data() {
            return {
                loading: false
            }
        },
        methods: {
            update: _.debounce(function (columnName, item) {
                this.loading = true;
                let table = this.$root.table;
                let url   = this.$root.url('/column/' + table);
                axios.put(url, {
                    column: columnName,
                    data: item
                }).then(() => {
                    this.loading = false;
                });
            }, 800)
        }
    }
</script>