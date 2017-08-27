<template>
    <tr>
        <td v-text="columnName"></td>
        <td>
            <b-input v-model="element.name" @input="$emit('update', columnName, element)"></b-input>
        </td>
        <td v-text="element.type"></td>
        <td v-text="element.length"></td>
    </tr>
</template>

<script>
    import _ from 'lodash';
    
    export default {
        props: ['columnName', 'element'],
    }
</script>