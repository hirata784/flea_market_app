# flea_market_app(模擬試験フリマアプリ)

## 環境構築
Dockerビルド
1. git clone git@github./hirata784/flea_market_app
2. DockerDesktopアプリを立ち上げる
3. docker-compose up -d --build

＊MySQLは、OSによって起動しない場合があるのでそれぞれのPCに合わせてdocker-compose.ymlファイルを編集して下さい。

Laravel環境構築
1. docker-compose exec php bash
2. composer install
3. env.exampleファイルから.envを作成。.envに以下の環境変数を追加
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
4. アプリケーションキーの作成
php artisan key:generate
5. マイグレーションの実行
php artisan migrate
6. シーディングの実行
php artisan db:seed

## 使用技術
- PHP 7.4.9
- Laravel 8.83.29
- MySQL 8.0.26

## ER図
![画像]

## URL
- 開発環境：http://localhost/
- phpMyAdmin：http://localhost:8080/