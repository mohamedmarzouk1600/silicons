<table>
    <thead>
    <tr>
        @foreach($headings as $heading)
            <th>{{$heading}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        <tr>
            @foreach($headings as $heading)
            <td>{{ $row->$heading }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
