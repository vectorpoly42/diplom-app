<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Добавить деталь</title>
    <script>
        function updateWearFields() {
            const wearCount = document.getElementById('wearsCount').value;
            const container = document.getElementById('wear-container');
            container.innerHTML = '';

            for (let i = 0; i < wearCount; i++) {
                const formGroup = document.createElement('div');
                formGroup.classList.add('form-group');

                const label = document.createElement('label');
                label.innerText = `Участок ${i + 1}:`;
                const input = document.createElement('input');
                input.type = 'number';
                input.step = '0.1';
                input.name = 'wear_areas[]';
                input.classList.add('form-control');
                input.required = true;
                formGroup.appendChild(label);
                formGroup.appendChild(input);
                container.appendChild(formGroup);
            }
        }
    </script>
</head>
<body>
@include('layout.menu')

<div class="container mt-5">
    <h2 class="mb-4">Добавить деталь</h2>
    <form action="{{ route('detail.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Название:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="initialDiameter">Изначальный диаметр:</label>
            <input type="number" step="0.1" class="form-control" id="initialDiameter" name="initialDiameter" required>
        </div>
        <div class="form-group">
            <label for="type">Тип:</label>
            <input type="number" class="form-control" id="type" name="type" required>
        </div>
        <div class="form-group">
            <label for="wearsCount">Количество участков повреждений:</label>
            <input type="number" class="form-control" id="wearsCount" name="wearsCount" value="1" min="1" onchange="updateWearFields()" required>
        </div>
        <div id="wear-container">
            <div class="form-group">
                <label>Участок 1:</label>
                <input type="number" step="0.1" class="form-control" name="wear_areas[]" required>
            </div>
        </div>
        <div class="form-group">
            <label for="wear">Износ</label>
            <input type="number" class="form-control" id="wear" name="wear">
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
</div>

</body>
</html>

