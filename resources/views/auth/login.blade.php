@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <div class="row row--nogutter top-line">
        <div class="line"></div>
    </div>
    <div class="main">
        <div class="row">
            <div class="row--small">
                <form action="{{ route('login.store') }}" method="POST" class="standalone-form">
                    @csrf
                    <h2>Форма входа</h2>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input id="password" type="password" name="password" required>
                    </div>
                    <div class="form-group form-links">
                        <a href="{{ route('register') }}">Нет аккаунта? Зарегистрируйтесь</a>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Войти</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
