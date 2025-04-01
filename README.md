# flea_market_app(模擬試験フリマアプリ)

## 環境構築
Dockerビルド
1. git clone git@github.com:hirata784/flea_market_app.git
2. DockerDesktopアプリを立ち上げる
3. cd flea_market_app
4. docker-compose up -d --build

＊MySQLは、OSによって起動しない場合があるのでそれぞれのPCに合わせてdocker-compose.ymlファイルを編集して下さい。

Laravel環境構築
1. docker-compose exec php bash
2. composer install
3. cp .env.example .env
4. .envに以下の環境変数を変更する
``` text
DB_HOST=mysql
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
SESSION_DRIVER=database
MAIL_HOST=mail
MAIL_FROM_ADDRESS=info@example.com
```
5. .envに以下の環境変数を追加する
``` text
STRIPE_KEY=公開可能キーを入力
STRIPE_SECRET=シークレットキーを入力
```
上記2行はstripeのテストAPIキーの公開鍵、秘密鍵を入力して下さい。  
アカウントを取得していない場合、下記URL欄のstripeからアカウントを作成して下さい。  

6. アプリケーションキーの作成
``` bash
php artisan key:generate
```
7. マイグレーションの実行
``` bash
php artisan migrate
```
8. シーディングの実行
``` bash
php artisan db:seed
```
9. シンボリックリンクの作成
``` bash
php artisan storage:link
```

単体テスト  
テスト用データベースの準備
1. PHPコンテナ上にいる場合、「exit」で抜ける
2. docker-compose exec mysql bash
3. mysql -u root -p
4. パスワードを求められるので「root」と入力しEnter
5. CREATE DATABASE demo_test;

テスト用の.envファイルの作成
1. MySQLコンテナ上にいる場合、「exit」で抜ける
2. docker-compose exec php bash
3. cp .env .env.testing
4. .env.testingの環境変数を変更する
``` text
APP_ENV=test
APP_KEY=
DB_DATABASE=demo_test
DB_USERNAME=root
DB_PASSWORD=root
```
5. php artisan key:generate --env=testing
6. php artisan config:clear
7. php artisan migrate --env=testing

メール認証の仕方
1. 会員登録画面より会員登録後、メール認証画面へ移動する
2. 下記URL欄のmailhogから、mailhogを開く
3. 登録したメールアドレスが記載されている本文をクリックする
4. [Verify Email Address]をクリックする  
時間内にクリック出来なかった場合、認証画面の[認証メールを再送する]を  
クリックして下さい。認証メールが再送されます.
5. メール認証が完了し、プロフィール編集画面へ移動する

決済画面の入力項目  
メールアドレス：メールアドレスを入力  
カード番号：4242 4242 4242 4242  
日付：未来の年月を入力  
(例：今日が2025年4月の場合、[04/25]以降は使用可能。[03/25]までは使用不可能。)  
CVC：3つの数値を入力(例：123)

## 使用技術
- PHP 7.4.9
- Laravel 8.83.29
- MySQL 8.0.26

## ER図
![画像](https://coachtech-lms-bucket.s3.ap-northeast-1.amazonaws.com/question/20250322103623_flea_market_app.png)
## URL
- 開発環境：http://localhost/
- 会員登録画面：http://localhost/register
- ログイン画面：http://localhost/login
- phpMyAdmin：http://localhost:8080/
- mailhog：http://localhost:8025/
- stripe：https://stripe.com/jp