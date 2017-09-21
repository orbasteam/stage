<template>
    
    <section class="section">
        <div class="columns">

            <div class="column is-narrow">
                <b-field>
                    <p class="control">
                        <span class="button is-static">Group</span>
                    </p>
                    <b-select v-model="currentGroup">
                        <option :value="group" v-for="group in groups" :key="group" v-text="group"></option>
                    </b-select>
                    
                    <p class="control">
                        <b-dropdown>

                            <button class="button is-outlined" slot="trigger">
                                <b-icon icon="arrow_drop_down"></b-icon>
                            </button>

                            <b-dropdown-item @click="destroyGroup" class="has-text-danger">
                                <b-icon icon="clear"></b-icon>
                                Destroy
                            </b-dropdown-item>
                            
                            <b-dropdown-item @click="createGroup" class="has-text-primary">
                                <b-icon icon="add"></b-icon>
                                Create
                            </b-dropdown-item>
                        </b-dropdown>
                    </p>
                </b-field>

            </div>

            <div class="column is-narrow">
                <list-per-page :option="option" 
                               :placeholder="defaultOptions.rowPerPage"
                               @update="updateList"
                ></list-per-page>
            </div>
            
        </div>

        <div is="draggable" v-model="list" :options="draggableOptions" class="columns is-multiline" @end="updateList">
            <div class="column is-4-tablet is-3-desktop is-2-fullhd item" v-for="element in list">
                <list-element :element="element" 
                              @update="updateList" 
                              @remove="remove"
                              :key="generateKey(element)"
                ></list-element>
            </div>
            
            <div class="column is-4-tablet is-3-desktop is-2-fullhd">
                <div class="card">
                    <div class="card-content">
                        <b-field>
                            <b-select v-model="newElement">
                                <option v-for="type in $elementTypes" :value="type.id" v-text="type.name"></option>
                            </b-select>
                            <p class="control">
                                <span class="button is-primary" @click="createElement">
                                    <b-icon icon="add"></b-icon>
                                </span>
                            </p>
                        </b-field>
                        
                    </div>
                </div>
            </div>
        </div>
        
    </section>

</template>

<style scoped>
    section.section {
        padding-top: 1rem;
    }
</style>

<script>

  import _ from 'lodash';
  import ListElement from './listElement';
  import ListPerPage from './listPerPage';
  import randomString from 'randomstring';
  import Draggable from 'vuedraggable';
  import Vue from 'vue';
  
  Vue.prototype.$elementTypes = [
    {id: 0, name: "Column/Method"},
    {id: 1, name: "Presenter"},
    {id: 2, name: "Enum"},
    {id: 3, name: "Custom"}
  ];

  export default {
    props: ['elements', 'options', 'defaultOptions'],
    data() {
      return {
        currentGroup: 'default',
        draggableOptions: {
          draggable: '.item',
          handle: '.handler'
        },
        option: {},
        newElement: 0
      }
    },
    methods: {

      updateListPath() {
        return this.$root.url('/list/update/' + this.$root.table + '/' + this.currentGroup);
      },

      updateList: _.debounce(function () {
        
        this.$root.startAjaxLoading();
        let items = [];
        
        _.map(this.list, (element) => {
          items.push(_.omitBy(element, _.isNil));
        });

        this.$set(this.elements, this.currentGroup, items);
        this.$set(this.options, this.currentGroup, this.option);
        
        axios.put(this.updateListPath(), {
          data: {
            data: items,
            option: this.option
          }
        }).then(() => {
          this.$root.finishAjaxLoading();
        });

      }, 800),

      generateKey(element) {

        if (!element.token) {
          element.token = randomString.generate(7);
        }

        return element.token;
      },
      
      destroyGroup() {

        let table = this.$root.table;
        let group = this.currentGroup;

        if (group === 'default') {
          this.$toast.open({
            message: `Default group can't be destroyed`,
            type: 'is-danger'
          });
          return;
        }

        this.$dialog.confirm({
          message: `Are you sure? You won't be able to revert this!`,
          onConfirm: () => {
            let url = this.$root.url('/list/destroyGroup/' + table + '/' + group);
            axios.delete(url)
              .then(() => {
                this.$delete(this.elements, group);
                this.currentGroup = this.groups.find((element) => {
                  return !!element;
                });
              }).catch((error) => {
              this.$toast.open({
                message: error.response.data,
                type: 'is-danger'
              })
            });
          }
        });
      },
      
      createElement() {

        if (!this.elements[this.currentGroup]) {
          this.$set(this.elements, this.currentGroup, []);
        }

        this.elements[this.currentGroup].push({
          type: this.newElement
        });
      },
      
      createGroup() {

        let table = this.$root.table;

        this.$dialog.prompt({
          message: `Enter group name`,
          placeholder: 'Group name',
          onConfirm: (group) => {
            let url = this.$root.url('/list/createGroup/' + table);
            axios.post(url, {
              group: group
            }).then(() => {
              this.$set(this.elements, group, []);
              this.currentGroup = group;
            }).catch(() => {
              this.$toast.open({
                message: 'Oops! something went wrong.',
                type: 'is-danger'
              })
            });
          }
        });
      },
      
      remove(element) {
        this.updateList();
        this._removeColumn(element);
      },
      
      _removeColumn(element) {
        let group = this.currentGroup;
        let key = _.findKey(this.elements[group], function (data) {
          return data['token'] === element.token;
        });

        this.elements[group].splice(key, 1);
      }
    },
    
    asyncComputed: {
      list() {
        return this.elements[this.currentGroup] || [];
      },
      
      groups() {
        let groups = _.keys(this.elements);
        return groups.length ? groups : ['default']; 
      },
      
      option() {
        
        if (this.options[this.currentGroup]) {
          return this.options[this.currentGroup];
        }
        
        return {
          paginate: true,
          actions: []
        };
      }
    },
    
    beforeMount() {
      this.option = {};
    },
    
    components: { ListElement, Draggable, ListPerPage },
  }

</script>