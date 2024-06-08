<!DOCTYPE html>
<html>
<head>
    <title>Результаты заявки</title>
</head>
<body>
<h1>Результаты заявки</h1>

@if (session('success'))
    <div>{{ session('success') }}</div>
@endif

<h2>Результаты расчетов</h2>
<p><strong>Количество деталей:</strong> {{ $order->number_of_details }}</p>
<p><strong>Количество приборов:</strong> {{ $order->number_of_devices }}</p>
<p><strong>J:</strong> {{ $order->J_parametr }}</p>

<h2>Детали:</h2>
<ul>
    @foreach ($details as $detail)
        <li>{{ $detail->name }}</li>
    @endforeach
</ul>

<!-- Здесь вы можете отобразить результаты расчетов -->
</body>
</html>