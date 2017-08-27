<template>
    <table class="table is-striped">
        <thead>
        <tr>
            <th>Column</th>
            <th>Name</th>
            <th>Type</th>
            <th>Length</th>
        </tr>
        </thead>
        <tbody>
        <tr is="column-tr" v-for="(element, columnName) in columns"
            :column-name="columnName" :element="element" @update="update"></tr>
        </tbody>
    </table>
</template>

<script>
    import ColumnTr from './columnTr';
    import _ from 'lodash';
    
    export default {
        props: ['columns'],
        methods: {
            update: _.debounce(function() {
                
                this.$root.startAjaxLoading();
                
                let table = this.$root.table;
                let url   = this.$root.url('/column/' + table);
                axios.put(url, {
                    data: this.columns
                }).then(() => {
                    this.$root.finishAjaxLoading();
                });

            }, 800)
        },
        components: { ColumnTr }
    }
</script>