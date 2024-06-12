<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Data List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
@include('layout.menu')
<div class="container mt-5">
    <h2>Excel Data List</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Номер листа</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($excelData as $data)
            <tr>
                <td>{{ $data->id }}</td>
                <td>{{ $data->sheet_name }}</td>
                <td>
                    <a href="{{ route('data.show', $data->id) }}" class="btn btn-primary">View</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
