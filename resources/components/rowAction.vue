<template>

    <div>
        <button type="button" class="button" @click="modalActive = true">
            <b-icon icon="touch_app"></b-icon>
        </button>

        <b-modal :active.sync="modalActive" :can-cancel="true" :width="960" :has-modal-card="true">
            <div class="modal-card" style="width: 850px">

                <header class="modal-card-head">
                    <p class="modal-card-title">Row Action</p>
                </header>

                <section class="modal-card-body">

                    <table class="table is-striped is-fullwidth">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>icon</th>
                                <th>text</th>
                                <th>url</th>
                            </tr>
                        </thead>
                        <draggable v-model="option.actions" 
                                   element="tbody" 
                                   :options="draggableOptions" 
                                   @end="$emit('update')">
                            <tr v-for="(action, index) in option.actions" class="row-action-item">
                                <td>
                                    <div class="movable tag is-medium">
                                        <b-icon icon="reorder" class="row-action-handler"></b-icon>
                                    </div>
                                </td>
                                <td>
                                    <button class="button is-danger is-outlined" @click="remove(index)">
                                        <b-icon icon="clear "></b-icon>
                                    </button>
                                </td>
                                <td>
                                    <b-input v-model="action.icon" @input="$emit('update')"></b-input>
                                </td>
                                <td>
                                    <b-input v-model="action.text" @input="$emit('update')"></b-input>
                                </td>
                                <td>
                                    <b-input v-model="action.uri" @input="$emit('update')" expanded></b-input>
                                </td>
                            </tr>
                        </draggable>
                        <tfoot>
                            <tr>
                                <td>
                                    <button type="button" class="button is-primary" @click="add">
                                        <b-icon icon="add"></b-icon>
                                    </button>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                </section>
                
            </div>
            
        </b-modal>
    </div>

</template>

<style scoped>
    .setting-icon {
        width: 20%;
    }

    .setting-text {
        width: 20%;
    }

    .setting-uri {
        width: 50%;
    }
</style>

<script>

  import Draggable from 'vuedraggable';
    
  export default {
    props: ['option'],
    data() {
      return {
        modalActive: false,
        draggableOptions: {
          draggable: '.row-action-item',
          handle: '.row-action-handler'
        }
      }
    },
    methods: {
      add() {
        this.option.actions.push({});
      },
      remove(index) {
        this.option.actions.splice(index, 1);
        this.$emit('update');
      }
    },
    components: { Draggable }
  }
</script>