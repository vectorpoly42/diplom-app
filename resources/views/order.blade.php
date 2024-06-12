<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Оформить заявку</title>
    <script>
        function updateDetailFields() {
            var numberOfDetails = document.getElementById('number_of_details').value;
            var detailFieldsContainer = document.getElementById('detail_fields_container');
            detailFieldsContainer.innerHTML = '';

            for (var i = 0; i < numberOfDetails; i++) {
                var formGroup = document.createElement('div');
                formGroup.classList.add('form-group');

                var label = document.createElement('label');
                label.innerText = 'Выберите деталь ' + (i + 1) + ':';
                label.setAttribute('for', 'detail_select_' + i);

                var select = document.createElement('select');
                select.name = 'detail_ids[]';
                select.classList.add('form-control', 'detail-select');
                select.setAttribute('data-index', i);
                select.id = 'detail_select_' + i;
                select.onchange = updateSelectOptions;

                @foreach ($details as $detail)
                var option = document.createElement('option');
                option.value = '{{ $detail->id }}';
                option.text = '{{ $detail->name }}';
                select.appendChild(option);
                @endforeach

                formGroup.appendChild(label);
                formGroup.appendChild(select);
                detailFieldsContainer.appendChild(formGroup);
            }
        }

        function updateSelectOptions() {
            var selects = document.getElementsByClassName('detail-select');
            var selectedValues = Array.from(selects).map(select => select.value);

            Array.from(selects).forEach(select => {
                var currentValue = select.value;
                select.innerHTML = '';

                @foreach ($details as $detail)
                var option = document.createElement('option');
                option.value = '{{ $detail->id }}';
                option.text = '{{ $detail->name }}';
                if (!selectedValues.includes(option.value) || option.value === currentValue) {
                    select.appendChild(option);
                }
                @endforeach

                    select.value = currentValue;
            });
        }

        function updateNumberOfDetailsField() {
            var numberOfDetailsInput = document.getElementById('number_of_details');
            var hiddenInput = document.getElementById('hidden_number_of_details');
            hiddenInput.value = numberOfDetailsInput.value;
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('number_of_details').addEventListener('change', updateNumberOfDetailsField);
        });
    </script>
</head>
<body>
@include('layout.menu')

<div class="container mt-5">
    <h1 class="mb-4">Оформить заявку</h1>

    <form action="{{ route('order.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="number_of_details">Количество деталей:</label>
            <input type="number" class="form-control" id="number_of_details" name="number_of_details" min="2" onchange="updateDetailFields()" required>
            <input type="hidden" id="hidden_number_of_details" name="hidden_number_of_details">
        </div>
        <div class="form-group" id="detail_fields_container"></div>
        <button type="submit" class="btn btn-primary">Отправить заявку</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
