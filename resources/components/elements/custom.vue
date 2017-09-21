<template>
    
    <div>

        <b-field label="Name">
            <b-input v-model="element.name" @input="$emit('update')"></b-input>
        </b-field>
        
        <b-field label="View">
            <b-input v-model="element.view" @input="$emit('update')"></b-input>
        </b-field>

        <b-field label="Params">
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th>Key</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <div is="customParam" 
                         :param.sync="param" 
                         v-for="(param, index) in element.params"
                         @remove="removeParam(index)"
                         @update="$emit('update')"></div>
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <button class="button is-primary is-outlined" type="button" @click="addParam">
                                <b-icon icon="add"></b-icon>
                            </button>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            
        </b-field>

    </div>
    
</template>

<script>
  
  import CustomParam from './customParam';
  export default {
    props: ['element'],
    methods: {
      addParam() {
        this.element.params.push({
          key: '',
          value: ''
        });
      },
      removeParam(index) {
        this.element.params.splice(index, 1);
        this.$emit('update');
      }
    },
    beforeMount() {
      if (!this.element.params) {
        this.element.params = [{
          key: '',
          value: ''
        }];
      }
    },
    components: { CustomParam }
  }
</script>