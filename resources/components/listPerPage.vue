<template>
    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <b-switch v-model="option.paginate" @click.native="update"></b-switch>
        </div>
        <b-field>
            <b-input v-model="option.rowPerPage" :placeholder="placeholder" @input="update" :disabled="disabled"></b-input>
            <p class="control">
                <span class="button is-static">row/page</span>
            </p>
        </b-field>
    </div>
    
</template>

<style lang="scss" scoped>
    .field {
        width: 200px;
        
        .field-label {
            margin-right: 0;
        }
    }
</style>

<script>
  export default {
    props: ['option', 'placeholder'],
    methods: {
      update() {
        this.$emit('update');
      }
    },
    asyncComputed: {
      disabled() {
        return this.option.paginate === false;
      }
    },
  }
</script>