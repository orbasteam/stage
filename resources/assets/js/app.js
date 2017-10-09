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

Vue.use(Buefy);
Vue.use(AsyncComputed);

window.Vue = Vue;

import Column from '../../components/column';
import List from '../../components/list';

const app = new Vue({
  el: '#app',
  data: {
    table: null,
    loading: true,
    ajaxLoading: false,
    routePrefix: 'stage-setup',
    activeTab: 'column',
    list: {},
    columns: {},
    enums: [],
    listOptions: {},
    listDefaultOptions: {},
  },
  methods: {
    isActiveTab(tab) {
      return tab === this.activeTab;
    },

    changeTab(tab) {
      this.activeTab = tab;
      localStorage.setItem('stage-tab', tab);
    },

    startAjaxLoading() {
      this.ajaxLoading = true;
    },

    finishAjaxLoading() {
      this.ajaxLoading = false;
    },

    url(uri) {
      return `/${this.routePrefix}/${_.trim(uri, '/')}`;
    },

    selectTable(value) {
      this.loading = true;

      axios.get(this.url(`/column/${this.table}`))
        .then((response) => {
          history.pushState({}, null, this.url('?table=' + this.table));

          this.list = response.data.list || {};
          this.columns = response.data.columns || {};
          this.listOptions = response.data.listOptions || {};

          this.loading = false;
        });
    },

    columnDefaultName(column) {
      return this.columns[column] ? this.columns[column].name : '';
    },

  },

  mounted() {
    this.loading = false;

    if (localStorage.getItem('stage-tab')) {
      this.activeTab = localStorage.getItem('stage-tab');
    }
  },

  components: { Column, List, Spinner },
});

window.app = app;