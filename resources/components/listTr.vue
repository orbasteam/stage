<template>
    <tr>
        <td>
            <button class="button is-danger is-outlined" @click="remove" :disabled="loading">
                <i class="fa fa-spinner fa-spin fa-fw" v-if="loading"></i>
                <b-icon icon="clear" v-if="!loading"></b-icon>
            </button>
        </td>
        <td>
            <span class="select">
                <b-select v-model="type" @input="update">
                    <option value="method">Column / Method</option>
                    <option value="presenter">Presenter</option>
                </b-select>
            </span>
        </td>
        <td>
            <b-autocomplete
                    v-model="mutableProps.element.column"
                    :placeholder="type"
                    :data="filterColumns"
                    @input="update">
            </b-autocomplete>
        </td>
        <td>
            <b-input v-model="mutableProps.element.name" :placeholder="defaultName" @input="update"></b-input>
        </td>
        <td>
            <b-input v-model="mutableProps.element.formatter" @input="update"></b-input>
        </td>
    </tr>
</template>

<script>
    
    import _ from 'lodash';
    
    export default {
        props: ['element'],
        data() {
            
            let type = this.element['presenter'] ? 'presenter' : 'method';
            
            return {
                type: type,
                loading: false,
                mutableProps: {
                    column: this.element.column,
                    element: this.element
                }
            }
        },
        methods: {
            update: _.debounce(function () {
                
                this.loading = true;
                
                let element = this.mutableProps.element;
                let originColumn = this.mutableProps.column;

                let item = {
                    name: element.name ? element.name : null,
                    presenter: this.type === 'presenter',
                    formatter: element.formatter ? element.formatter : null,
                    column: element.column ? element.column : null,
                    token: element.token
                };

                if (!item.column) {
                    this.remove();
                    return;
                }

                this.$emit('update', originColumn, item);
                this.mutableProps.element = item;
                this.mutableProps.column  = item.column;

                axios.put(this.updatePath(), {
                    column: originColumn,
                    data: item
                }).then(() => {
                    this.loading = false;
                });
                
            }, 800),
            updatePath() {
                return this.$root.url('/list/update/' + this.$root.table + '/' + this.$parent.currentGroup);
            },
            remove() {
                this.$emit('remove', this.mutableProps.element);
            }
        },
        computed: {
            filterColumns() {
                let columns = _.keys(this.$root.columns);
                return columns.filter((column) => {
                    return column.toLowerCase().indexOf(this.mutableProps.element.column) >= 0;
                });
            },
            presenter() {
                return this.type === 'presenter';
            },
            defaultName() {
                let column = this.$root.columns[this.mutableProps.column];
                return column ? column.name : '';
            }
        },
        mounted() {
            this.$set(this.mutableProps.element, 'column', this.mutableProps.column);
        }
    }
</script>