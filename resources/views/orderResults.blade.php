<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результаты заявки</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
@csrf
@include('layout.menu')

<div class="container mt-5">
    <h1 class="mb-4">Результаты заявки</h1>

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title">Результаты расчетов</h2>
            <p class="card-text"><strong>Количество деталей:</strong> {{ $order->number_of_details }}</p>
            <p class="card-text"><strong>Количество приборов:</strong> {{ $order->number_of_devices }}</p>
            <p class="card-text"><strong>J:</strong> {{ $order->J_parameter }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title">Детали:</h2>
            <ul class="list-group list-group-flush">
                @foreach ($details as $detail)
                    <li class="list-group-item">{{ $detail->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="card mb-4">
    <a href="{{ route('order.create') }}" class="btn btn-primary mb-2">Создать новую заявку</a>
    <a href="{{ route('order.download', ['id' => $order->id]) }}" class="btn btn-secondary">Выгрузить результаты</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
