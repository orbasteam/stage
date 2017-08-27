
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

import Column from '../../components/column';
import List from '../../components/list';

const app = new Vue({
    el: '#app',
    data: {
        table: [],
        loading: true,
        ajaxLoading: false,
        routePrefix: 'stage-setup',
        activeTab: 0,
        list: {},
        columns: {}
    },
    methods: {
        startAjaxLoading() {
            this.ajaxLoading = true;
        },
        finishAjaxLoading() {
            this.ajaxLoading = false;
        },
        url(uri) {
            return '/' + this.routePrefix + '/' + _.trim(uri, '/');
        },
        selectTable() {
            
            this.loading = true;
            
            axios.get(this.url('/column/' + this.table))
                .then( (response) => {
                    history.pushState({}, null, this.url('?table=' + this.table));
                    
                    if (response.data.list) {
                        this.list = response.data.list;
                    }
                    
                    if (response.data.columns) {
                        this.columns = response.data.columns;
                    }
                    
                    this.loading = false;
                });
        },
        empty(obj) {
            return Object.keys(obj).length === 0;
        }
    },
    mounted() {
        this.loading = false;
    },
    components: {Column, List, Spinner},
});

window.app = app;