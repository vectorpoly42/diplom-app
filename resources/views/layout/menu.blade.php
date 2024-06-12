<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="{{ route('main.index') }}">Главная</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('detail.allDetails') }}">Справочник деталей</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('detail.create') }}">Добавить деталь</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('order.create') }}">Создать заявку</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('order.index') }}">Все заявки</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('upload.form') }}">Загрузить расписание</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('data.list') }}">Загруженные расписания</a></li>
            </ul>
        </div>
    </div>
</nav>
