<template>
    <tr>
        <td>
            <button class="button is-danger is-outlined" @click="$emit('remove')">
                <b-icon icon="clear"></b-icon>
            </button>
        </td>
        <td>
            <b-input v-model="param.key" @input="$emit('update')"></b-input>
        </td>
        <td>
            <b-input v-model="param.value" @input="$emit('update')"></b-input>
        </td>
    </tr>
</template>

<script>
  export default {
    props: ['param']
  }
</script>