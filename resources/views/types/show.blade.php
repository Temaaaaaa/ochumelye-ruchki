@extends('layouts.app')

@section('title', $creativityType->title)

@section('content')
    <div class="main">
        <div class="row">
            <div class="hover"></div>
            <div class="title">{{ $creativityType->title }}</div>
            <div class="row--small grid between">
                <div class="content content--type">
                    <img src="{{ asset($creativityType->image ?: 'img/elifant.png') }}" alt="{{ $creativityType->title }}">
                    <p>{{ $creativityType->description }}</p>

                    @guest
                        <div class="hint-box">
                            Запись на мастер-классы доступна после авторизации.
                            <a href="{{ route('login') }}">Войти</a>
                            или
                            <a href="{{ route('register') }}">зарегистрироваться</a>.
                        </div>
                    @endguest
                </div>

                @include('partials.sidebar', ['activeTypeId' => $creativityType->id])
            </div>

            <div class="row shedule">
                <div class="row--small">
                    <h2>Расписание</h2>
                    <div class="drivers">
                        @forelse ($masterClasses as $masterClass)
                            @php
                                $slotKey = $masterClass->date->format('Y-m-d').'|'.$masterClass->time_slot;
                                $alreadyEnrolled = isset($userEnrollmentIds[$masterClass->id]);
                                $conflictsWithExisting = isset($userBookedSlots[$slotKey]) && ! $alreadyEnrolled;
                            @endphp

                            <div class="driver grid">
                                <div class="driver-left grid">
                                    <div class="driver-photo">
                                        <img src="{{ asset($masterClass->teacher->photo ?: 'img/driver1.png') }}" alt="{{ $masterClass->teacher->full_name }}">
                                    </div>
                                    <div class="driver-text">
                                        <div class="driver-name">{{ $masterClass->teacher->full_name }}</div>
                                        <div class="driver-desc">
                                            <div><strong>{{ $masterClass->title }}</strong></div>
                                            <div>{{ $masterClass->description }}</div>
                                            <div class="class-meta">
                                                <span>Дата: {{ $masterClass->formatted_date }}</span>
                                                <span>Время: {{ $masterClass->formatted_time_slot }}</span>
                                                <span>Стоимость: {{ number_format($masterClass->price, 2, ',', ' ') }} руб.</span>
                                                <span>Свободных мест: {{ $masterClass->seats_left }} из {{ $masterClass->max_people }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="driver-right">
                                    @auth
                                        @if (auth()->user()->isVisitor())
                                            @if ($alreadyEnrolled)
                                                <div class="status-badge">Вы записаны</div>
                                            @elseif ($conflictsWithExisting)
                                                <div class="status-badge status-badge--muted">На это время уже есть запись</div>
                                            @elseif ($masterClass->is_full)
                                                <div class="status-badge status-badge--muted">Мест нет</div>
                                            @else
                                                <a class="driver-btn driver-btn--link" href="{{ route('bookings.confirm', $masterClass) }}">Записаться</a>
                                            @endif
                                        @endif
                                    @endauth

                                    @if ($masterClass->is_full && ! auth()->user()?->isVisitor())
                                        <div class="status-badge status-badge--muted">Мест нет</div>
                                    @endif

                                    <div class="driver-time">
                                        {{ $masterClass->formatted_date }}<br>
                                        {{ $masterClass->formatted_time_slot }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-block">По этому виду творчества мастер-классы пока не запланированы.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
