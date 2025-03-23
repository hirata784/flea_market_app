# flea_market_app(模擬試験フリマアプリ)

## 環境構築
Dockerビルド
1. git clone git@github.com:hirata784/flea_market_app.git
2. DockerDesktopアプリを立ち上げる
3. docker-compose up -d --build

＊MySQLは、OSによって起動しない場合があるのでそれぞれのPCに合わせてdocker-compose.ymlファイルを編集して下さい。

Laravel環境構築
1. docker-compose exec php bash
2. composer install
3. env.exampleファイルから.envを作成。.envに以下の環境変数を追加
``` text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
SESSION_DRIVER=database
MAIL_HOST=mail
MAIL_FROM_ADDRESS=info@example.com
STRIPE_KEY=公開可能キーを入力
STRIPE_SECRET=シークレットキーを入力
```
STRIPE_KEY=公開可能キーを入力  
STRIPE_SECRET=シークレットキーを入力  
上記2行はstripeのテストAPIキーの公開鍵、秘密鍵を入力して下さい。  
アカウントを取得していない場合、下記URL欄のstripeからアカウントを作成して下さい。  
4. アプリケーションキーの作成
``` bash
php artisan key:generate
```
5. マイグレーションの実行
``` bash
php artisan migrate
```
6. シーディングの実行
``` bash
php artisan db:seed
```

単体テストの準備
1. cp .env .env.testing
2. .env.testingの環境変数を変更
``` text
APP_ENV=test
APP_KEY=
DB_DATABASE=demo_test
DB_USERNAME=root
DB_PASSWORD=root
```
3. php artisan key:generate --env=testing
4. php artisan config:clear
5. php artisan migrate --env=testing

## 使用技術
- PHP 7.4.9
- Laravel 8.83.29
- MySQL 8.0.26

## ER図
![画像](https://coachtech-lms-bucket.s3.ap-northeast-1.amazonaws.com/question/20250322103623_flea_market_app.png)
## URL
- 開発環境：http://localhost/
- phpMyAdmin：http://localhost:8080/
- mailhog：http://localhost:8025/
- stripe：https://stripe.com/jp