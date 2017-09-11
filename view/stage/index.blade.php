@extends('stage::layout')

@section('content')

    <section class="section" v-show="isActiveTab('column')">
        <column :columns="columns"></column>
    </section>

    <list :elements="list"
          :enums="enums"
          :default-options="listDefaultOptions"
          :options="listOptions"
          v-show="isActiveTab('list')"
    ></list>
    
@stop