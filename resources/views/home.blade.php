@extends('layouts.app')

@section('title', 'Очумелые ручки')

@section('content')
    <div class="main">
        <div class="row">
            <div class="hover"></div>
            <div class="title">Виды творчества</div>
            <div class="row--small grid between">
                <div class="content">
                    <img src="{{ asset('img/elifant.png') }}" alt="Творчество">
                    <p>
                        «Очумелые ручки» помогают детям и взрослым открыть для себя мир прикладного творчества.
                        На сайте можно познакомиться с направлениями обучения, посмотреть расписание мастер-классов
                        и выбрать занятие по душе.
                    </p>
                    <p>
                        Мы объединяем архитектурное моделирование, кулинарию и художественную резьбу по дереву.
                        Каждое направление ведут практикующие мастера, а занятия проходят в небольших группах,
                        чтобы каждому участнику хватило внимания преподавателя.
                    </p>

                    <div class="type-list">
                        @foreach ($types as $type)
                            <div class="type-card">
                                <h2>{{ $type->title }}</h2>
                                <p>{{ \Illuminate\Support\Str::limit($type->description, 310) }}</p>
                                <div class="type-card__meta">
                                    <span>Мастер-классов в расписании: {{ $type->master_classes_count }}</span>
                                    <a class="btn" href="{{ route('types.show', $type) }}">Открыть расписание</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @auth
                        @if (auth()->user()->isVisitor())
                            <div class="account-box">
                                <div class="driver-page-my">Мои записи</div>
                                @if ($enrollments->isEmpty())
                                    <p>Вы пока не записаны на мастер-классы.</p>
                                @else
                                    <div class="booking-list">
                                        @foreach ($enrollments as $enrollment)
                                            <div class="booking-card">
                                                <div class="booking-card__title">{{ $enrollment->masterClass->title }}</div>
                                                <div>Вид творчества: {{ $enrollment->masterClass->creativityType->title }}</div>
                                                <div>Дата: {{ $enrollment->masterClass->formatted_date }}</div>
                                                <div>Время: {{ $enrollment->masterClass->formatted_time_slot }}</div>
                                                <div>Мастер: {{ $enrollment->masterClass->teacher->full_name }}</div>
                                                <div>Стоимость: {{ number_format($enrollment->masterClass->price, 2, ',', ' ') }} руб.</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @elseif (auth()->user()->isTeacher())
                            <div class="account-box">
                                <a class="btn" href="{{ route('cabinet.index') }}">Перейти в личный кабинет</a>
                            </div>
                        @endif
                    @endauth
                </div>

                @include('partials.sidebar')
            </div>
        </div>
    </div>
@endsection
