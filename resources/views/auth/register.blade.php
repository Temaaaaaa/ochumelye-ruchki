@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <div class="row row--nogutter top-line">
        <div class="line"></div>
    </div>
    <div class="main">
        <div class="row">
            <div class="row--small">
                <form action="{{ route('register.store') }}" method="POST" class="standalone-form">
                    @csrf
                    <h2>Форма регистрации</h2>
                    <div class="form-group">
                        <label for="full_name">ФИО</label>
                        <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="+79991234567" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input id="password" type="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Зарегистрироваться</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
