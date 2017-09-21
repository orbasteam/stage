<template>

    <div>
        <b-field label="Column">
            <b-input v-model="element.column" @input="$emit('update')"></b-input>
        </b-field>

        <b-field label="Name">
            <b-input v-model="element.name" @input="$emit('update')" :placeholder="defaultName"></b-input>
        </b-field>

        <b-field label="Enum">
            <b-input v-model="element.enum" @input="$emit('update')"></b-input>
        </b-field>

        <b-field label="Formatter">
            <b-input v-model="element.formatter" @input="$emit('update')"></b-input>
        </b-field>
        
    </div>
    
</template>

<script>
  export default {
    props: ['element'],
    computed: {
      defaultName() {
        return this.$root.columnDefaultName(this.element.column);
      }
    }
  }
</script>