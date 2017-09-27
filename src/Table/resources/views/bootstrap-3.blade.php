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

{!! $table->paginator() !!}