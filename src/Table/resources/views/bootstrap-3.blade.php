<table class="table">
    <thead>
    <tr>
        @foreach($table->getHeader()->items() as $item)
            <th>
                {!! $item->render() !!}
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($table->getBody()->items() as $item)
        <tr>
            @foreach($item as $value)
                <td>{!! $value !!}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>

<div class="row">
    <div class="col-md-6">
        {!! $table->paginator() !!}
    </div>
    <div class="col-md-6">
        <p class="text-right">
            total <span class="label label-default">{{$table->getBody()->getPaginator()->total()}}</span> records
        </p>
    </div>
</div>
