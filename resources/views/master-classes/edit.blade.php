@extends('layouts.app')

@section('title', 'Редактирование мастер-класса')

@section('content')
    <div class="row row--nogutter top-line">
        <div class="line"></div>
    </div>
    <div class="main">
        <div class="row">
            <div class="row--small">
                <form action="{{ route('master-classes.update', $masterClass) }}" method="POST" class="standalone-form">
                    @csrf
                    @method('PATCH')
                    <h2>Редактирование мастер-класса</h2>

                    <div class="detail-grid detail-grid--form">
                        <div><strong>Вид творчества:</strong> {{ $masterClass->creativityType->title }}</div>
                        <div><strong>Название:</strong> {{ $masterClass->title }}</div>
                        <div><strong>Дата:</strong> {{ $masterClass->formatted_date }}</div>
                        <div><strong>Время:</strong> {{ $masterClass->formatted_time_slot }}</div>
                    </div>

                    <div class="form-group">
                        <label for="description">Описание мастер-класса</label>
                        <textarea id="description" name="description" required>{{ old('description', $masterClass->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">Стоимость мастер-класса</label>
                        <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $masterClass->price) }}" required>
                    </div>

                    <div class="form-group form-actions">
                        <button type="submit" class="btn">Сохранить изменения</button>
                        <a class="btn" href="{{ route('cabinet.index') }}">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
