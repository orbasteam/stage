@extends('stage::layout')

@section('content')
    
    <section class="section" v-if="isActiveTab('column')">
        <div class="container">
            <column :columns="columns"></column>
        </div>
    </section>

    <section class="section" v-if="isActiveTab('list')">
        <div class="container">
            <list :elements="list"></list>
        </div>
    </section>
        
@stop