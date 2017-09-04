<template>
    <b-field style="width: 150px">
        <b-input v-model="option.rowPerPage" :placeholder="placeholder" @input="update"></b-input>
        <p class="control">
            <span class="button is-static">row/page</span>
        </p>
    </b-field>
</template>

<script>
  export default {
    props: ['option', 'placeholder'],
    methods: {
      update() {
        this.$emit('update');
      }
    }
  }
</script>