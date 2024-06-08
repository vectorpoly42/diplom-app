<!DOCTYPE html>
<html>
<head>
    <title>Submit Request</title>
</head>
<body>
<h1>Оформить заявку</h1>

<form action="{{ route('order.store') }}" method="POST">
    @csrf
    <label for="detail_id">Выберите детали (от двух) </label>
    <select name="detail_id" id="detail_id">
        @foreach ($details as $detail)
            <option value="{{ $detail->id }}">{{ $detail->name }}</option>
        @endforeach
    </select>
    <button type="submit">Отправить заявку</button>
</form>
</body>
</html>
