<!DOCTYPE html>
<html>
<head>
    <title>Детали</title>
</head>
<body>
@include('layout.menu')
<h1>Заявки</h1>
<table>
    <thead>
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
</body>
</html>
