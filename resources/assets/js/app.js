
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import _ from 'lodash';
import Buefy from 'buefy';
import AsyncComputed from 'vue-async-computed'

import Spinner from 'vue-loading-spinner/src/components/RotateSquare2';

import 'buefy/lib/buefy.css';

Vue.use(Buefy);
Vue.use(AsyncComputed);

window.Vue = Vue;


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import ColumnTr from '../../components/columnTr';
import List from '../../components/list';

const app = new Vue({
    el: '#app',
    data: {
        table: [],
        data: {},
        loading: true,
        routePrefix: 'stage-setup',
        activeTab: 0
    },
    methods: {
        url(uri) {
            return '/' + this.routePrefix + '/' + _.trim(uri, '/');
        },
        selectTable() {
            
            this.loading = true;
            
            axios.get(this.url('/column/' + this.table))
                .then( (response) => {
                    history.pushState({}, null, this.url('?table=' + this.table));
                    this.data = response.data;
                    this.loading = false;
                });
        },
        empty(obj) {
            return Object.keys(obj).length === 0;
        }
    },
    computed: {
        columns: function() {
            return this.data['columns'] ? this.data['columns'] : {};
        },
        list: function() {
            return this.data['list'] ? this.data['list'] : [];
        }
    },
    mounted() {
        this.loading = false;
    },
    components: {ColumnTr, List, Spinner},
});

window.app = app;