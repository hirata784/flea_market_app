<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Attendance Management</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header-inner">
            <div class="header-utilities">
                <img src="{{ asset('storage/images/logo.svg') }}" width="300" height="80">
                <form class="form-search" action="/search" method="get">
                    @csrf
                    <input type="text" class="search-txt" name="keyword" placeholder="なにをお探しですか?">
                    @if(Request::is('/'))
                    @if ($data == null)
                    <input type="hidden" name="tab" value="">
                    @elseif ($data == 'mylist')
                    <input type="hidden" name="tab" value="mylist">
                    @endif
                    @endif
                    <button class="search-btn">検索</button>
                </form>
                <nav>
                    <ul class="nav-items">
                        @if (Auth::check())
                        <li class="nav-item">
                            <form action="/logout" method="post">
                                @csrf
                                <button class="nav-btn">ログアウト</button>
                            </form>
                        </li>
                        @else
                        <li class="nav-item">
                            <form action="/login" method="get">
                                @csrf
                                <button class="nav-btn" href="/login">ログイン</button>
                            </form>
                        </li>
                        @endif
                        <li class="nav-item">
                            <form action="/mypage" method="post">
                                @csrf
                                <button class="nav-btn">マイページ</button>
                            </form>
                        </li>
                        <li class="nav-item">
                            <form action="/sell" method="get">
                                @csrf
                                <button class="listing-btn">出品</button>
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
