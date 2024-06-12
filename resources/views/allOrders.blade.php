<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Детали</title>
</head>
<body>
@include('layout.menu')

<div class="container mt-5">
    <h1 class="mb-4">Заявки</h1>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
        <tr>
            <th>Номер</th>
            <th>Количество приборов</th>
            <th>Количество деталей</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->number_of_devices }}</td>
                <td>{{ $order->number_of_details }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
