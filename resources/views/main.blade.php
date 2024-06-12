<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Добро пожаловать!</title>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Добро пожаловать!</h1>
    <p class="lead">Выберите один из разделов ниже, чтобы начать работу:</p>
    <ul class="list-group">
        <li class="list-group-item"><a href="{{ route('detail.allDetails') }}">Справочник деталей</a></li>
        <li class="list-group-item"><a href="{{ route('detail.create') }}">Добавить деталь</a></li>
        <li class="list-group-item"><a href="{{ route('order.create') }}">Создать заявку</a></li>
        <li class="list-group-item"><a href="{{ route('order.index') }}">Все заявки</a></li>
        <li class="list-group-item"><a href="{{ route('upload.form') }}">Загрузить расписание</a></li>
        <li class="list-group-item"><a href="{{ route('data.list') }}">Загруженные расписания</a></li>
    </ul>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

