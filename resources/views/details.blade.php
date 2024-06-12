<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Детали</title>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const availableTypes = @json($availableTypes);

            const filterInput = document.getElementById('filterType');
            filterInput.addEventListener('input', function() {
                validateType(this.value, availableTypes);
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
<title>Все детали</title>
</head>
<body>
@include('layout.menu')

<div class="container mt-5">
    <h1 class="mb-4">Все детали</h1>

    <!-- Поле для фильтрации -->
    <div class="form-group">
        <label for="filterType">Фильтр по типу:</label>
        <input type="text" class="form-control" id="filterType" list="typeOptions">
        <datalist id="typeOptions">
            @foreach ($availableTypes as $type)
                <option value="{{ $type }}"></option>
            @endforeach
        </datalist>
    </div>

    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
        <tr>
            <th>Название</th>
            <th>Диаметр</th>
            <th>Тип</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($details as $detail)
            <tr>
                <td>{{ $detail['name'] }}</td>
                <td>{{ $detail['diameter'] }}</td>
                <td>{{ $detail['type'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
