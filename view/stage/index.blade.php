@extends('stage::layout')

@section('content')

    <section class="section">
        <column :columns="columns" v-show="isActiveTab('column')"></column>

        <list :elements="list"
              :enums="enums"
              :default-options="listDefaultOptions"
              :options="listOptions"
              v-show="isActiveTab('list')"
        ></list>
    </section>
    
@stop