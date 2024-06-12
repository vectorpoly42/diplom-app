<!DOCTYPE html>
<html>
<head>
    <title>Submit Request</title>
    <script>
        function updateDetailFields() {
            var numberOfDetails = document.getElementById('number_of_details').value;
            var detailFieldsContainer = document.getElementById('detail_fields_container');
            detailFieldsContainer.innerHTML = '';

            for (var i = 0; i < numberOfDetails; i++) {
                var label = document.createElement('label');
                label.innerText = 'Выберите деталь ' + (i + 1) + ':';
                var select = document.createElement('select');
                select.name = 'detail_ids[]';
                select.classList.add('detail-select');
                select.setAttribute('data-index', i);
                select.onchange = updateSelectOptions;

                @foreach ($details as $detail)
                var option = document.createElement('option');
                option.value = '{{ $detail->id }}';
                option.text = '{{ $detail->name }}';
                select.appendChild(option);
                @endforeach

                detailFieldsContainer.appendChild(label);
                detailFieldsContainer.appendChild(select);
                detailFieldsContainer.appendChild(document.createElement('br'));
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

        // Слушает изменения в поле количества деталей
        document.getElementById('number_of_details').addEventListener('change', updateNumberOfDetailsField);
    </script>
</head>
<body>
@include('layout.menu')
<h1>Оформить заявку</h1>

<form action="{{ route('order.store') }}" method="POST">
    @csrf
    <label for="number_of_details">Количество деталей:</label>
    <input type="number" id="number_of_details" name="number_of_details" min="2" onchange="updateDetailFields()">
    <input type="hidden" id="hidden_number_of_details" name="hidden_number_of_details">
    <br><br>
    <div id="detail_fields_container"></div>
    <button type="submit">Отправить заявку</button>
</form>
</body>
</html>
