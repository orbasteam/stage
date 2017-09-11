<template>
    <tr>
        <td>
            <div class="movable tag is-medium">
                <b-icon icon="reorder" class="handler"></b-icon>
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
            <b-autocomplete
                    v-model="element.enum"
                    :data="enums"
                    @input="update">
            </b-autocomplete>
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
          {id: 2, name: "Enum"},
          {id: 3, name: "Action"}
        ]
      };
    },
    methods: {
      update() {
        this.$emit('update');
      },
      remove() {
        this.$emit('remove', this.element);
      },
      filter(data, match) {
        match = match ? match.toLowerCase() : '';
        return data.filter((item) => {
          return item.toLowerCase().indexOf(match) >= 0;
        });
      }
    },
    computed: {
      enums() {
        return this.filter(this.$root.enums, this.element.enum);
      },
      filterColumns() {
        let columns = _.keys(this.$root.columns);
        return this.filter(columns, this.element.column);
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
      if (this.element.type === undefined) {
        this.$set(this.element, 'type', 0);
      }

    }
  }
</script>