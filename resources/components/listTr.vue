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
                <b-select v-model="element.type" @input="update">
                    <option v-for="option in elementTypes" :value="option.id" v-text="option.name"></option>
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
            <b-input v-model="element.enum" @input="update"></b-input>
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
                elementTypes: [
                    {id: 0, name: "Column/Method"},
                    {id: 1, name: "Presenter"},
                    {id: 2, name: "Enum"}
                ]
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