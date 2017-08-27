@extends('stage::layout')

@section('content')

    <div class="container">

        <b-tabs v-model="activeTab" type="is-boxed">

            <b-tab-item label="Column">
                <column :columns="columns"></column>
            </b-tab-item>

            <b-tab-item label="List">
                <list :elements="list"></list>
            </b-tab-item>

        </b-tabs>

    </div>
    
@stop