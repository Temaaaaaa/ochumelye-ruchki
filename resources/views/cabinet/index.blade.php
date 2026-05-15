@extends('layouts.app')

@section('title', 'Личный кабинет')
@section('body-class', 'dp')

@section('content')
    <div class="main">
        <div class="row">
            <div class="hover"></div>
            <div class="title">Личный кабинет</div>
            <div class="row--small grid between">
                <div class="content driver-page">
                    <div class="driver-page-photo">
                        <img src="{{ asset($master->photo ?: 'img/driver-page.png') }}" alt="{{ $master->full_name }}">
                    </div>
                    <div class="driver-page-name">{{ $master->full_name }}</div>
                    <div class="driver-page-text">
                        <div class="driver-page-my">Мои мастер-классы</div>

                        @forelse ($masterClasses as $masterClass)
                            <div class="cabinet-class">
                                <div class="cabinet-class__head">
                                    <div>
                                        <strong>{{ $masterClass->title }}</strong>
                                        <div>{{ $masterClass->creativityType->title }}</div>
                                    </div>
                                    <div class="cabinet-class__actions">
                                        <a class="btn btn--small" href="{{ route('cabinet.show', $masterClass) }}">Участники</a>
                                        <a class="btn btn--small" href="{{ route('master-classes.edit', $masterClass) }}">Редактировать</a>
                                    </div>
                                </div>
                                <div class="cabinet-class__meta">
                                    <span>{{ $masterClass->formatted_date }}</span>
                                    <span>{{ $masterClass->formatted_time_slot }}</span>
                                    <span>{{ number_format($masterClass->price, 2, ',', ' ') }} руб.</span>
                                    <span>Мест: {{ $masterClass->max_people }}</span>
                                    <span>Свободно: {{ $masterClass->seats_left }}</span>
                                </div>
                                <p>{{ $masterClass->description }}</p>

                                <div class="participants-box">
                                    <strong>Участники:</strong>
                                    @if ($masterClass->enrollments->isEmpty())
                                        <p>Пока никто не записался.</p>
                                    @else
                                        <ul class="participants-list">
                                            @foreach ($masterClass->enrollments as $enrollment)
                                                <li>
                                                    {{ $enrollment->user->full_name }} |
                                                    email: {{ $enrollment->user->email }} |
                                                    тел.: {{ $enrollment->user->phone }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p>У вас пока нет созданных мастер-классов.</p>
                        @endforelse
                    </div>
                    <div class="driver-page-btn-wrapper">
                        <a class="driver-page-btn btn" href="{{ route('master-classes.create') }}">
                            Добавить мастер-класс
                        </a>
                    </div>
                </div>

                @include('partials.sidebar')
            </div>
        </div>
    </div>
@endsection
