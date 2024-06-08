<!DOCTYPE html>
<html>
{{--<nav>--}}
{{--    <ul>--}}
{{--        //оставить заявку - выполнить расчеты для одного вида детали--}}
{{--        //посмотреть все заявки - просмотреть прошлые расчеты--}}
{{--        //посмотреть все детали - просмотреть список + переход на все детали--}}
{{--        //вывести отчет по времени всех деталей--}}
{{--    </ul>--}}
{{--</nav>--}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить деталь</title>
    <script>
        function updateWearFields() {
            const wearCount = document.getElementById('wearCount').value;
            const container = document.getElementById('wear-container');
            container.innerHTML = '';

            for (let i = 0; i < wearCount; i++) {
                const label = document.createElement('label');
                label.innerText = `Участок ${i + 1}:`;
                const input = document.createElement('input');
                input.type = 'number';
                input.step = '0.1';
                input.name = 'wear[]';
                input.required = true;
                container.appendChild(label);
                container.appendChild(input);
                container.appendChild(document.createElement('br'));
            }
        }
    </script>
</head>
<body>
<form action="{{ route('detail.store') }}" method="POST">
    @csrf
    <div>
        <label for="name">Название:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div>
        <label for="initialDiameter">Изначальный диаметр:</label>
        <input type="number" step="0.1" id="initialDiameter" name="initialDiameter" required>
    </div>
    <div>
        <label for="type">Тип:</label>
        <input type="number" id="type" name="type" required>
    </div>
    <div>
        <label for="wearCount">Количество участков повреждений:</label>
        <input type="number" id="wearCount" name="wearCount" value="1" min="1" onchange="updateWearFields()" required>
    </div>
    <div id="wear-container">
        <label>Участок 1:</label>
        <input type="number" step="0.1" name="wear[]" required>
        <br>
    </div>
    <button type="submit">Добавить</button>
</form>
</body>

</html>
