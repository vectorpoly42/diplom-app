<!DOCTYPE html>
<html>
<head>
    <title>Детали</title>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            {{--const availableTypes = @json($availableTypes);--}}

            const filterInput = document.getElementById('filterType');
            filterInput.addEventListener('input', function() {
                validateType(this.value, $availableTypes);
                filterDetails(this.value);
            });

            function validateType(value, types) {
                if (!types.includes(value) && value !== '') {
                    filterInput.setCustomValidity('Тип не существует');
                } else {
                    filterInput.setCustomValidity('');
                }
            }

            function filterDetails(filterValue) {
                const rows = document.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const type = row.querySelector('td:nth-child(3)').textContent;
                    if (type.includes(filterValue) || filterValue === '') {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        });
    </script>
</head>
<body>
@include('layout.menu')
<h1>Все детали</h1>

<!-- Поле для фильтрации -->
<div>
    <label for="filterType">Фильтр по типу:</label>
    <input type="text" id="filterType" list="typeOptions">
    <datalist id="typeOptions">
        @foreach ($availableTypes as $type)
            <option value="{{ $type }}"></option>
        @endforeach
    </datalist>
</div>

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
