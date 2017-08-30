@extends('stage::layout')

@section('content')
    
    <section class="section" v-if="isActiveTab('column')">
        <column :columns="columns"></column>
    </section>
    
    <section class="section" v-if="isActiveTab('list')">
        <list :elements="list"></list>
    </section>
        
@stop