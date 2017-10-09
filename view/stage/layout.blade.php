<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="{{ route('stage-setup.image', ['image' => 'favicon.ico']) }}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{ route('stage-setup.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons|Bowlby+One+SC">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Stage setting</title>
</head>
<body>

<div id="app">

    <div class="page-spinner" v-show="loading">
        <spinner></spinner>
    </div>

    <nav class="navbar is-primary">
        
        <div class="container">
            
            <div class="navbar-brand">
                <a class="is-brand nav-item" href="{{ route('stage-setup.index') }}">
                    <svg width="45" height="45" xmlns="http://www.w3.org/2000/svg" style="vector-effect: non-scaling-stroke;" stroke="null">
                        <title style="vector-effect: non-scaling-stroke;" stroke="null"/>

                        <g stroke="null">
                            <title stroke="null">background</title>
                            <rect stroke="null" fill="none" id="canvas_background" height="47" width="47" y="-1" x="-1"/>
                        </g>
                        <g stroke="null">
                            <title stroke="null">Layer 1</title>
                            <path stroke="null" id="svg_1" fill="#ffffff" d="m35.479729,17.845977l0,-3.083659a47.523676,47.523676 0 0 1 1.445465,-9.154611s0,0 0,0a0.786975,0.786975 0 0 0 0.160607,-0.481822c0,-1.991529 -7.853693,-2.409108 -14.45465,-2.409108s-14.45465,0.417579 -14.45465,2.409108a0.770915,0.770915 0 0 0 0.128486,0.417579s0,0 0,0a46.174575,46.174575 0 0 1 1.477586,9.218854l0,3.083659c-4.432759,0.947583 -6.424289,3.212144 -6.424289,7.355811c0,5.219735 8.833397,9.636433 19.272866,9.636433s19.272866,-4.416698 19.272866,-9.636433c0,-4.127605 -1.991529,-6.424289 -6.424289,-7.355811zm-1.606072,6.424289a0.770915,0.770915 0 0 1 -0.385457,0.690611c-1.220615,0.738793 -4.320334,1.846983 -10.857048,1.846983s-9.636433,-1.10819 -10.857048,-1.78274a0.770915,0.770915 0 0 1 -0.385457,-0.690611l0,-1.943347a37.871182,37.871182 0 0 0 11.242505,1.204554a37.871182,37.871182 0 0 0 11.242505,-1.204554l0,1.879104z"/>
                        </g>
                    </svg>
                    <span class="brand-name">STAGE</span>
                </a>
            </div>

            <div class="navbar-menu">

                <div class="navbar-start">
                    <a href="#" class="navbar-item is-tab" :class="{'is-active': isActiveTab('column')}" @click.prevent="changeTab('column')">Column</a>
                    <a href="#" class="navbar-item is-tab" :class="{'is-active': isActiveTab('list')}" @click.prevent="changeTab('list')">List</a>
                </div>

                <div class="navbar-end">

                    <div class="nav-item" v-if="ajaxLoading">
                        <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                    </div>

                    <b-dropdown v-model="table" position="is-bottom-left">
                        <a class="navbar-item" slot="trigger">
                            <span v-if="table" v-text="table"></span>
                            <span v-else>Select Table</span>
                            <b-icon icon="arrow_drop_down"></b-icon>
                        </a>
                        @foreach($tables as $table)
                            <b-dropdown-item @click="selectTable" value="{{$table}}">{{$table}}</b-dropdown-item>
                        @endforeach
                    </b-dropdown>

                </div>
            </div>
            
        </div>
        
    </nav>

    @yield('content')
    
</div>

<script src="{{ route('stage-setup.js') }}"></script>

@yield('script')

<script>
    app.routePrefix = '{{ config('stage.global.route') }}';
    @if(request('table'))
        app.table = '{{ request('table') }}';
        app.selectTable();
    @endif
    
    @if($enums)
        app.enums = {!! $enums->toJson() !!}
    @endif
    
    @if($tableDefaultOptions)
        app.listDefaultOptions = {!! json_encode($tableDefaultOptions, JSON_FORCE_OBJECT) !!}
    @endif
    
</script>

</body>
</html>