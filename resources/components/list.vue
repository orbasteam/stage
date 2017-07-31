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
                <th>Type</th>
                <th>Column / Presenter</th>
                <th>Name</th>
                <th>Formatter</th>
            </tr>
            </thead>
            <tbody>
            <tr is="list-tr" :element="element" v-for="element in list"
                @remove="remove" @update="update" :key="generateKey(element)"></tr>
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
            </tr>
            </tbody>
        </table>

    </div>

</template>

<script>

    import _ from 'lodash';
    import ListTr from './listTr';
    import randomString from 'randomstring';

    export default {
        props: ['elements'],
        data() {
            return {
                currentGroup: 'default',
                mutableElements: this.elements
            }
        },
        watch: {
            elements() {
                this.mutableElements = this.elements;
            }
        },
        methods: {
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
                                this.$delete(this.mutableElements, group);
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
                    this.$set(this.mutableElements, this.currentGroup, []);
                }

                this.mutableElements[this.currentGroup].push({});
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
                            this.$set(this.mutableElements, group, []);
                            this.currentGroup = group;
                        }).catch(() => {
                            this.$toast.open({
                                message: 'oops! something is error',
                                type: 'is-danger'
                            })
                        });
                    }
                });
            },
            update(column, element) {
                let group = this.currentGroup;
                
                if (column !== element.column) {

                    let elements = _.map(this.mutableElements[group], function(value) {
                        if (value.column === column) {
                            return element;
                        }
                        
                        return value;
                    });
                    
                    this.$set(this.mutableElements, group, elements);
                }
            },
            remove(element) {
                
                let table = this.$root.table;
                let group = this.currentGroup;
                
                if (!element.column) {
                    this._removeColumn(element);
                    return;
                }
                
                this.$dialog.confirm({
                    message: `Are you sure？ You won't be able to revert this!`,
                    onConfirm: () => {
                        let url = this.$root.url('/list/destroy/' + table + '/' + group + '/' + element.column);
                        axios.delete(url)
                            .then(() => {
                                this._removeColumn(element);
                            }).catch((error) => {
                                this.$toast.open({
                                    message: error.response.data,
                                    type: 'is-danger'
                                });
                            });
                    }
                });
            },
            _removeColumn(element) {
                let group = this.currentGroup;
                let key = _.findKey(this.mutableElements[group], function(data){
                    return data['token'] === element.token;
                });
                
                this.mutableElements[group].splice(key, 1);
            }
        },
        computed: {
            list() {
                return this.mutableElements[this.currentGroup];
            },
            groups() {
                let groups = _.keys(this.mutableElements);

                if (!groups.length) {
                    return ['default'];
                }

                return groups;
            }
        },
        components: { ListTr }
    }
    
</script>