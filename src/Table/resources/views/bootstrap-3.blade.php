<table class="table">
    <thead>
    <tr>
        @foreach($table->getHeader() as $item)
            <th>{{ $item['name'] }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($table->getBody() as $item)
        <tr>
            @foreach($table->getHeader() as $header)
                <td>
                    {!! $item[$header['column']] !!}
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>