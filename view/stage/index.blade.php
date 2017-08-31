@extends('stage::layout')

@section('content')
    
    <section class="section" v-show="isActiveTab('column')">
        <column :columns="columns"></column>
    </section>
    
    <section class="section" v-show="isActiveTab('list')">
        <list :elements="list" :presenters="presenters" :enums="enums"></list>
    </section>
        
@stop