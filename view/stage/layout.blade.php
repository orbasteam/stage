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


    <nav class="nav">

        <div class="container">

            <div class="nav-left">
                <a class="nav-item is-brand" href="{{ route('stage-setup.index') }}">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%">
                        <title/>
                        <g data-name="Cylinder Hat" id="Cylinder_Hat">
                            <path d="M20,11.42V9.5a29.59,29.59,0,0,1,.9-5.7s0,0,0,0A.49.49,0,0,0,21,3.5C21,2.26,16.11,2,12,2S3,2.26,3,3.5a.48.48,0,0,0,.08.26s0,0,0,0A28.75,28.75,0,0,1,4,9.5v1.92c-2.76.59-4,2-4,4.58,0,3.25,5.5,6,12,6s12-2.75,12-6C24,13.43,22.76,12,20,11.42Zm-1,4a.48.48,0,0,1-.24.43C18,16.31,16.07,17,12,17s-6-.69-6.76-1.11A.48.48,0,0,1,5,15.46V14.25A23.58,23.58,0,0,0,12,15a23.58,23.58,0,0,0,7-.75Z"
                                  style="fill:#303c42"/>
                        </g>
                    </svg>
                    <span class="brand-name">STAGE</span>
                </a>
            </div>

            <div class="nav-right nav-menu">

                <div class="nav-item" v-if="ajaxLoading">
                    <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                </div>

                <div class="nav-item">

                    <b-field>
                        <p class="control">
                            <span class="button is-static">Table</span>
                        </p>
                        <b-select placeholder="Select Table" @change="selectTable" v-model="table">
                            @foreach($tables as $table)
                                <option>{{$table}}</option>
                            @endforeach
                        </b-select>
                    </b-field>

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
    @endif
        app.data = {!! isset($columns) ? $columns->toJson() : '{}' !!};
</script>

</body>
</html>