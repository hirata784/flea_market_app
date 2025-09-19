<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Attendance Management</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shipped.css') }}">
</head>

<body>
    <h1>{{ $order['seller'] }}様</h1>
    <p>
        {{ $order['purchaser'] }}様より取引が完了しました。
    </p>
    <p>
        下記の内容をご確認ください。
    </p>
    <p>注文内容詳細</p>
    <p>
        ・注文商品:{{ $order['item'] }}
    </p>
    <p>
        ・金額:{{ $order['price'] }}円
    </p>
    <p>配送先</p>
    <p>
        ・郵便番号:{{ $order['post_code'] }}
    </p>
    <p>
        ・住所:{{ $order['address'] }}
    </p>
    <p>
        ※このメールは自動送信です。
    </p>
</body>

</html>