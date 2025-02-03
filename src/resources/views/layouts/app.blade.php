<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                <img src="{{ asset('storage/images/logo.svg') }}" width="300" height="80">

                <form class="form-search" action="/search" method="get">
                    @csrf
                    <input type="text" class="search-txt" name="keyword" placeholder="なにをお探しですか?">
                </form>
                <nav>
                    <ul class="header-nav">
                        @if (Auth::check())
                        <li class="header-nav__item">
                            <form class="form-nav" action="/logout" method="post">
                                @csrf
                                <button class="header-nav__button">ログアウト</button>
                            </form>
                        </li>
                        @else
                        <li class="header-nav__item">
                            <form class="form-nav" action="/login" method="get">
                                @csrf
                                <a class="login__button-submit" href="/login">ログイン</a>
                            </form>
                        </li>
                        @endif
                        <li class="header-nav__item">
                            <form class="form-nav" action="">
                                @csrf
                                <button class="header-nav__button">マイページ</button>
                            </form>
                        </li>
                        <li class="header-nav__item">
                            <form class="form-nav" action="">
                                @csrf
                                <button class="listing-nav__button">出品</button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>