<template>

    <div>
        <b-field label="Column">
            <b-autocomplete
                    v-model="element.column"
                    :data="columns"
                    :keep-first="true"
                    @input="$emit('update')">
            </b-autocomplete>
        </b-field>

        <b-field label="Name">
            <b-input v-model="element.name" @input="$emit('update')" :placeholder="defaultName"></b-input>
        </b-field>

        <b-field label="Enum">
            <b-autocomplete
                    v-model="element.enum"
                    :data="enums"
                    :keep-first="true"
                    @input="$emit('update')">
            </b-autocomplete>
        </b-field>

        <b-field label="Formatter">
            <b-input v-model="element.formatter" @input="$emit('update')"></b-input>
        </b-field>

        <b-field label="Order">
            <b-switch v-model="element.order" @input="$emit('update')"></b-switch>
        </b-field>

    </div>

</template>

<script>
  import _ from 'lodash';
  export default {
    props: ['element'],
    computed: {
      defaultName() {
        return this.$root.columnDefaultName(this.element.column);
      },
      enums() {
        return this.filter(this.$root.enums, this.element.enum);
      },
      columns() {
        let columns = _.keys(this.$root.columns);
        return this.filter(columns, this.element.column);
      }
    },
    methods: {
      filter(data, match) {
        match = match ? match.toLowerCase() : '';
        return data.filter((item) => {
          return item.toLowerCase().indexOf(match) >= 0;
        });
      },
    },
    beforeMount() {
      _.forEach(['column', 'enum'], key => {
        if (this.element[key] === undefined) {
          this.element[key] = '';
        }
      });
    },
  }
</script>

