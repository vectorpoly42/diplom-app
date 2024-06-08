<!DOCTYPE html>
<html>
<head>
    <title>Успешная заявка</title>
</head>
<body>
<h1>Заявка создана успешно</h1>

<p>Вы успешно создали заявку.</p>

<a href="{{ route('request.results', ['id' => $order->id]) }}">
    <button>Показать результаты</button>
</a>

<a href="{{ route('request.create') }}">
    <button>Создать новую заявку</button>
</a>
</body>
</html>
