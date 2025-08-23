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
                    <input type="text" class="search-txt" name="keyword" placeholder="なにをお探しですか?">
                    <!-- マイリスト選択時に検索→マイリストのまま結果表示 -->
                    @if(Request::is('/'))
                    @if ($data == null)
                    <input type="hidden" name="tab" value="">
                    @elseif ($data == 'mylist')
                    <input type="hidden" name="tab" value="mylist">
                    @endif
                    @endif
                    <button class="search-btn">検索</button>
                </form>
                <div class="hamburger" id="hamburger">
                    <div class="icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <nav class="sm">
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
                                <button class="nav-btn" href="/login">ログイン</button>
                            </form>
                        </li>
                        @endif
                        <li class="nav-item">
                            <form action="/mypage" method="get">
                                <button class="nav-btn">マイページ</button>
                            </form>
                        </li>
                        <li class="nav-item">
                            <form action="/sell" method="get">
                                <button class="listing-btn">出品</button>
                            </form>
                        </li>
                    </ul>
                </nav>
                <nav class="pc">
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
                                <button class="nav-btn" href="/login">ログイン</button>
                            </form>
                        </li>
                        @endif
                        <li class="nav-item">
                            <form action="/mypage" method="get">
                                <button class="nav-btn">マイページ</button>
                            </form>
                        </li>
                        <li class="nav-item">
                            <form action="/sell" method="get">
                                <button class="listing-btn">出品</button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $('#hamburger').on('click', function() {
            $('.icon').toggleClass('close');
            $('.sm').slideToggle();
        });
    </script>
    <main>
        @yield('content')
    </main>
</body>

</html>