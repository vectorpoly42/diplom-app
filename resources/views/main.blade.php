<!DOCTYPE html>
<h1>Добро пожаловать!</h1>
<p>Выберите один из разделов ниже, чтобы начать работу:</p>
<ul>
    <li><a href="{{ route('main.index') }}">Главная</a></li>
    <li><a href="{{ route('detail.allDetails') }}">Детали</a></li>
    <li><a href="{{ route('detail.create') }}">Добавить деталь</a></li>
    <li><a href="{{ route('order.create') }}">Создать заявку</a></li>
    <li><a href="{{ route('order.index') }}">Все заявки</a></li>
</ul>
</html>
