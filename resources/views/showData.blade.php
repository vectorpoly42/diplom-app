<!-- showData.blade.php -->

<h2>Лист: {{ $excelData->sheet_name }}</h2>
<table class="table table-bordered">
    <thead>
    <tr>
        @foreach(array_keys($excelData->data[0]) as $header)
            <th>{{ $header }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($excelData->data as $row)
        <tr>
            @foreach($row as $cell)
                <td>{{ $cell }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
<a href="{{ route('data.list') }}" class="btn btn-secondary">Вернуться к списку</a>
