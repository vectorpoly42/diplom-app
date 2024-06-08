{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<nav>--}}
{{--    <ul>--}}
{{--        //оставить заявку - выполнить расчеты для одного вида детали--}}
{{--        //посмотреть все заявки - просмотреть прошлые расчеты--}}
{{--        //посмотреть все детали - просмотреть список + переход на все детали--}}
{{--        //вывести отчет по времени всех деталей--}}
{{--    </ul>--}}
{{--</nav>--}}
{{--</html>--}}

    <!DOCTYPE html>
<html>
<head>
    <title>Детали</title>
</head>
<body>
<h1>Все детали</h1>

<table>
    <thead>
    <tr>
        <th>Название</th>
        <th>Диаметр</th>
        <th>Тип</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($details as $detail)
        <tr>
            <td>{{ $detail->name }}</td>
            <td>{{ $detail->diameter }}</td>
            <td>{{ $detail->type }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
