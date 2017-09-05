<template>
    
    <div>
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
        
        <div class="columns">
            
            <div class="column">
                <div class="box">
                    <table class="table is-striped is-fullwidth">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>Type</th>
                            <th>Method</th>
                            <th>Name</th>
                            <th>Enum</th>
                            <th>Formatter</th>
                        </tr>
                        </thead>
                        <draggable v-model="list" element="tbody" :options="draggableOptions" @end="updateList">
                            <tr is="list-tr" :element="element" v-for="element in list"
                                @remove="remove" @update="updateList" class="item"
                                :key="generateKey(element)"></tr>
                        </draggable>
                        <tfoot>
                        <tr>
                            <td>
                                <button class="button is-info is-outlined" @click="createElement">
                                    <b-icon icon="add"></b-icon>
                                </button>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>

</template>

<script>

  import _ from 'lodash';
  import ListTr from './listTr';
  import ListPerPage from './listPerPage';
  import randomString from 'randomstring';
  import Draggable from 'vuedraggable';

  export default {
    props: ['elements', 'options', 'defaultOptions'],
    data() {
      return {
        currentGroup: 'default',
        draggableOptions: {
          draggable: '.item',
          handle: '.handler'
        },
        option: {}
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

        this.elements[this.currentGroup].push({});
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
          paginate: true
        };
      }
    },
    beforeMount() {
      this.option = {};
    },
    components: { ListTr, Draggable, ListPerPage },
  }

</script>