<template>
    <tr>
        <td>
            <div class="movable tag is-medium">
                <b-icon icon="drag_handle" class="handler"></b-icon>
            </div>
        </td>
        <td>
            <button class="button is-danger is-outlined" @click="remove" :disabled="loading">
                <b-icon icon="clear"></b-icon>
            </button>
        </td>
        <td>
            <span class="select">
                <b-select v-model="element.presenter" @input="update">
                    <option :value="0">Column / Method</option>
                    <option :value="1">Presenter</option>
                </b-select>
            </span>
        </td>
        <td>
            <b-autocomplete
                    v-model="element.column"
                    :data="filterColumns"
                    @input="update">
            </b-autocomplete>
        </td>
        <td>
            <b-input v-model="element.name" :placeholder="defaultName" @input="update"></b-input>
        </td>
        <td>
            <b-input v-model="element.formatter" @input="update"></b-input>
        </td>
    </tr>
</template>

<script>
    
    import _ from 'lodash';
    
    export default {
        props: ['element'],
        data() {
            return {
                loading: false,
            };
        },
        methods: {
            update() {
                this.$emit('update');
            },
            remove() {
                this.$emit('remove', this.element);
            }
        },
        computed: {
            filterColumns() {
                let columns = _.keys(this.$root.columns);
                return columns.filter((column) => {
                    return column.toLowerCase().indexOf(this.element.column) >= 0;
                });

            },
            defaultName() {
                
                if (!this.element.column) {
                    return '';
                }
                
                let column = this.$root.columns[this.element.column];
                return column ? column.name : '';
            }
        },
        mounted() {
            let presenter = this.element.presenter ? this.element.presenter : 0;
            this.$set(this.element, 'presenter', presenter);
        }
    }
</script>