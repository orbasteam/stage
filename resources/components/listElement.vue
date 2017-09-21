<template>

    <div class="card">
        <header class="card-header">
            
            <p class="card-header-title">
                <span class="tag handler">
                    <b-icon icon="drag_handle"></b-icon>
                </span>
                 
                <span v-text="typeName"></span>
            </p>

        </header>

        <div class="card-content is-paddingless">
            <div class="content">
                <table class="table" v-if="content">
                    <tr v-for="(value, key) in content">
                        <th v-text="key"></th>
                        <td class="value" v-text="value"></td>
                    </tr>
                </table>
            </div>
        </div>
        <footer class="card-footer">
            <a @click="isModalActive = true" class="card-footer-item">Edit</a>
            <a @click="remove" class="card-footer-item">Remove</a>
        </footer>

        <b-modal :active.sync="isModalActive" :can-cancel="true" :width="500">

            <div>
                <header class="modal-card-head">
                    <p class="modal-card-title" v-text="typeName"></p>
                </header>
                <section class="modal-card-body">
                    <div :is="elementType" :element="element" @update="$emit('update')"></div>
                </section>

                <footer class="modal-card-foot">
                </footer>

            </div>
            
        </b-modal>

    </div>
    
</template>

<style scoped>
    .handler {
        cursor: move;
    }
    .table {
        margin-bottom: 0;
    }
    
    .table .value {
        word-break: break-all;
    }
</style>

<script>

  import _ from 'lodash';
  import ElementColumn from './elements/elementColumn';
  import ElementCustom from './elements/custom';
    
  export default {
    props: ['element'],
    data() {
      return {
        isModalActive: false
      }
    },
    asyncComputed: {
      typeName() {
        
        let object = _.find(this.$elementTypes, {id: this.element.type});
        return object ? object.name : '';
      },
      content() {
        
        switch(parseInt(this.element.type)) {
          case 0:
          case 1:
          case 2:
            return this.columnContent;
          
          case 3:
            return this.customContent; 
        }
      },
      columnContent() {
        
        let name = this.element.name ? this.element.name : null;
        
        if (!name) {
          let column = this.$root.columns[this.element.column];
          name = column ? column.name : '';
        }

        return {
          Column: this.element.column,
          Name: name
        };
      },
      customContent() {
        return {
          name: this.element.name,
          view: this.element.view
        }
      }
    },
    computed: {
      elementType() {
        switch (parseInt(this.element.type)) {
          
          case 0:
          case 1:
          case 2:
            return 'element-column';
            
          case 3:
            return 'element-custom';
        }
      }
    },
    methods: {
      remove() {
        this.$emit('remove', this.element);
      }
    },
    components: { ElementColumn, ElementCustom }
  }
</script>