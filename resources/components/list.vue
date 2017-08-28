<template>

    <div>

        <div class="columns">
            <div class="column">
                <b-field>
                    <p class="control">
                        <span class="button is-static">Group</span>
                    </p>
                    <b-select v-model="currentGroup">
                        <option :value="group" v-for="group in groups" :key="group" v-text="group"></option>
                    </b-select>
                </b-field>
            </div>
            <div class="column is-11">
                <button class="button is-danger is-outlined" @click="destroyGroup" type="button">Destroy Group</button>
                <button class="button is-info is-outlined" @click="createGroup" type="button">Create Group</button>
            </div>
        </div>
        
        <table class="table is-striped">
            <thead>
            <tr>
                <th></th>
                <th></th>
                <th>Type</th>
                <th>Column / Presenter</th>
                <th>Name</th>
                <th>Formatter</th>
            </tr>
            </thead>
            <draggable v-model="list" element="tbody" :options="draggableOptions" @end="updateList">
                <tr is="list-tr" :element="element" v-for="element in list"
                    @remove="remove" @update="updateList" class="item" 
                    :key="generateKey(element)"></tr>
                
                <tr slot="footer">
                    <td>
                        <button class="button is-info is-outlined" @click="createElement">
                            <b-icon icon="add"></b-icon>
                        </button>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </draggable>
        </table>

    </div>

</template>

<script>

    import _ from 'lodash';
    import ListTr from './listTr';
    import randomString from 'randomstring';
    import Draggable from 'vuedraggable';

    export default {
        props: ['elements'],
        data() {
            return {
                currentGroup: 'default',
                draggableOptions: {
                    draggable:'.item', 
                    handle: '.handler'
                }
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
                    
                    let item = {};
                    _.each(['name', 'formatter', 'column', 'token', 'presenter'], (field) => {
                        if (element[field] !== undefined) {
                            item[field] = element[field];
                        }
                    });
                    items.push(item);
                });

                this.$set(this.elements, this.currentGroup, items);
                
                axios.put(this.updateListPath(), {
                    data: items
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
                            }).catch( (error) => {
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
                let key = _.findKey(this.elements[group], function(data){
                    return data['token'] === element.token;
                });
                
                this.elements[group].splice(key, 1);
            }
        },
        asyncComputed: {
            list() {
                if (this.elements) {
                    return this.elements[this.currentGroup];
                }
                
                return [];
            },
            groups() {
                let groups = _.keys(this.elements);

                if (!groups.length) {
                    return ['default'];
                }

                return groups;
            }
        },
        components: { ListTr, Draggable }
    }
    
</script>