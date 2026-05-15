@extends('layouts.app')

@section('title', 'Подтверждение записи')

@section('content')
    <div class="row row--nogutter top-line">
        <div class="line"></div>
    </div>
    <div class="main">
        <div class="row">
            <div class="row--small">
                <div class="confirm-box">
                    <h2>Подтверждение записи</h2>
                    <div class="detail-grid">
                        <div><strong>ФИО пользователя:</strong> {{ auth()->user()->full_name }}</div>
                        <div><strong>Вид творчества:</strong> {{ $masterClass->creativityType->title }}</div>
                        <div><strong>ФИО мастера:</strong> {{ $masterClass->teacher->full_name }}</div>
                        <div><strong>Мастер-класс:</strong> {{ $masterClass->title }}</div>
                        <div><strong>Дата:</strong> {{ $masterClass->formatted_date }}</div>
                        <div><strong>Время:</strong> {{ $masterClass->formatted_time_slot }}</div>
                        <div><strong>Стоимость:</strong> {{ number_format($masterClass->price, 2, ',', ' ') }} руб.</div>
                    </div>

                    <div class="form-actions">
                        <form action="{{ route('bookings.store', $masterClass) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn">Подтвердить</button>
                        </form>
                        <form action="{{ route('bookings.cancel', $masterClass) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn">Отмена</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
