@extends('layouts.app')

@section('title', $masterClass->title)
@section('body-class', 'dp')

@section('content')
    <div class="main">
        <div class="row">
            <div class="hover"></div>
            <div class="title">{{ $masterClass->title }}</div>
            <div class="row--small grid between">
                <div class="content detail-card">
                    <h2>{{ $masterClass->title }}</h2>
                    <div class="detail-grid">
                        <div><strong>Вид творчества:</strong> {{ $masterClass->creativityType->title }}</div>
                        <div><strong>Ведущий:</strong> {{ $masterClass->teacher->full_name }}</div>
                        <div><strong>Дата:</strong> {{ $masterClass->formatted_date }}</div>
                        <div><strong>Время:</strong> {{ $masterClass->formatted_time_slot }}</div>
                        <div><strong>Стоимость:</strong> {{ number_format($masterClass->price, 2, ',', ' ') }} руб.</div>
                        <div><strong>Группа:</strong> {{ $masterClass->enrollments->count() }}/{{ $masterClass->max_people }}</div>
                        <div><strong>Свободно:</strong> {{ $masterClass->seats_left }}</div>
                    </div>

                    <div class="detail-section">
                        <strong>Описание</strong>
                        <p>{{ $masterClass->description }}</p>
                    </div>

                    <div class="detail-section">
                        <strong>Список участников</strong>
                        @if ($masterClass->enrollments->isEmpty())
                            <p>На мастер-класс пока никто не записался.</p>
                        @else
                            <ul class="participants-list">
                                @foreach ($masterClass->enrollments as $enrollment)
                                    <li>{{ $enrollment->user->full_name }} | {{ $enrollment->user->email }} | {{ $enrollment->user->phone }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="detail-actions">
                        <a class="btn" href="{{ route('master-classes.edit', $masterClass) }}">Редактировать</a>
                        <a class="btn" href="{{ route('cabinet.index') }}">Назад в кабинет</a>
                    </div>
                </div>

                @include('partials.sidebar', ['activeTypeId' => $masterClass->creativity_type_id])
            </div>
        </div>
    </div>
@endsection
