<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Очумелые ручки')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body class="@yield('body-class')">
    <div class="header">
        <div class="row grid middle between">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="Логотип клуба Очумелые ручки">
                </a>
            </div>
            <div class="title">
                Клуб любителей творчества «Очумелые ручки»
            </div>
            <div class="auth auth--wide">
                @auth
                    <div class="auth-user">
                        <span>{{ auth()->user()->full_name }}</span>
                        @if (auth()->user()->isTeacher())
                            <a href="{{ route('cabinet.index') }}">Личный кабинет</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit">Выход</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}">Вход / Регистрация</a>
                @endauth
            </div>
        </div>
    </div>

    <div class="row row--nogutter">
        <div class="menu-burger">
            <div class="burger">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>

    @include('partials.alerts')

    @yield('content')

    <div class="row row--nogutter">
        <div class="line"></div>
    </div>
    <div class="footer">
        <div class="row">
            <div class="row--small grid between">
                <div class="address">Наш адрес: ВДНХ, 120В</div>
                <div class="tel">Тел.: +7 (912) 345-67-65</div>
                <div class="copy">(с) Очумелые ручки, {{ now()->year }}</div>
            </div>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
