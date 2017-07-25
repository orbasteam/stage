@extends('stage::layout')

@section('content')

    <div class="container">

        <b-tabs v-model="activeTab" type="is-boxed">
            
            <b-tab-item label="Column">

                <table class="table is-striped">
                    <thead>
                    <tr>
                        <th>Column</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Length</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr is="column-tr" v-for="(element, columnName) in columns" 
                        :column-name="columnName" :element="element"></tr>
                    </tbody>
                </table>

            </b-tab-item>
            
            <b-tab-item label="List">
                <list :elements="list"></list>
            </b-tab-item>
            
        </b-tabs>
        
    </div>
    
@stop