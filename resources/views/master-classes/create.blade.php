@extends('layouts.app')

@section('title', 'Добавить мастер-класс')

@section('content')
    <div class="row row--nogutter top-line">
        <div class="line"></div>
    </div>
    <div class="main">
        <div class="row">
            <div class="row--small">
                <form action="{{ route('master-classes.store') }}" method="POST" class="standalone-form">
                    @csrf
                    <h2>Форма добавления мастер-класса</h2>
                    <p class="form-note">
                        Мастер-классы проводятся по фиксированной сетке: 09:00-11:00, 11:00-13:00, 13:00-15:00, 15:00-17:00.
                    </p>

                    <div class="form-group">
                        <label for="creativity_type_id">Вид творчества</label>
                        <select id="creativity_type_id" name="creativity_type_id" required>
                            <option value="">Выберите направление</option>
                            @foreach ($creativityTypes as $type)
                                <option value="{{ $type->id }}" @selected(old('creativity_type_id') == $type->id)>{{ $type->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title">Название мастер-класса</label>
                        <input id="title" type="text" name="title" value="{{ old('title') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Описание мастер-класса</label>
                        <textarea id="description" name="description" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="date">Дата</label>
                        <input id="date" type="date" name="date" value="{{ old('date') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="time_slot">Время</label>
                        <select id="time_slot" name="time_slot" required>
                            <option value="">Выберите время</option>
                            @foreach ($timeSlots as $value => $label)
                                <option value="{{ $value }}" @selected(old('time_slot') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="max_people">Количество человек в группе</label>
                        <input id="max_people" type="number" name="max_people" min="1" value="{{ old('max_people') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="price">Стоимость мастер-класса</label>
                        <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const occupiedSlotsByDate = @json($occupiedSlotsByDate);
        const dateSelect = document.getElementById('date');
        const timeSelect = document.getElementById('time_slot');

        function syncTimeOptions() {
            const selectedDate = dateSelect.value;
            const occupiedSlots = occupiedSlotsByDate[selectedDate] || [];

            [...timeSelect.options].forEach((option) => {
                if (!option.value) {
                    return;
                }

                const baseText = option.textContent.replace(' - занято', '');
                const isOccupied = occupiedSlots.includes(option.value);
                option.disabled = isOccupied;
                option.textContent = isOccupied ? `${baseText} - занято` : baseText;
            });

            if (timeSelect.selectedOptions[0]?.disabled) {
                timeSelect.value = '';
            }
        }

        dateSelect.addEventListener('change', syncTimeOptions);
        syncTimeOptions();
    </script>
@endsection
