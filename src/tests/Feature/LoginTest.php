<?php

namespace Tests\Feature;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    // 2.ログイン機能
    public function testログイン機能_メールアドレス未入力エラー()
    {
        $response = $this->get('/login'); // ログインページを開く
        $response->assertStatus(200);

        // テスト用ユーザーを作成
        $user = User::factory([
            'name' => 'testuser',
            'email' => 'abcd@example.com',
            'password' => 'password',
        ])->create();
        // データベースにデータが存在するかをチェック
        $this->assertDatabaseHas('users', [
            'name' => 'testuser',
            'email' => 'abcd@example.com',
            'password' => 'password',
        ]);

        $requestParams = [
            'email' => null,
            'password' => 'password',
        ];

        $request = new LoginRequest(); // インスタンスを生成
        $rules = $request->rules(); // バリデーションルールを取得

        /** @var \Illuminate\Validation\Validator */
        $validator = Validator::make($requestParams, $rules); // ダミーデータをバリデーションに通す

        $actualMessages = $validator->messages()->get('email'); // 実際のバリデーションメッセージを取得
        $expectedMessage = 'メールアドレスを入力してください'; // 期待するバリデーションメッセージ
        $response = $this->get('/mypage/profile'); // 認証済みのみ入れるページへ遷移
        $response->assertStatus(302);

        $this->assertSame( // 期待するメッセージと実際のメッセージを比較する
            $expectedMessage,
            $actualMessages[\array_search($expectedMessage, $actualMessages, true)]
        );
    }

    public function testログイン機能_パスワード未入力エラー()
    {
        $response = $this->get('/login'); // ログインページを開く
        $response->assertStatus(200);

        $requestParams = [
            'email' => 'abcd@example.com',
            'password' => "",
        ];

        $request = new LoginRequest(); // インスタンスを生成
        $rules = $request->rules(); // バリデーションルールを取得

        /** @var \Illuminate\Validation\Validator */
        $validator = Validator::make($requestParams, $rules); // ダミーデータをバリデーションに通す

        $actualMessages = $validator->messages()->get('password'); // 実際のバリデーションメッセージを取得
        $expectedMessage = 'パスワードを入力してください'; // 期待するバリデーションメッセージ
        $response = $this->get('/mypage/profile'); // 認証済みのみ入れるページへ遷移
        $response->assertStatus(302);

        $this->assertSame( // 期待するメッセージと実際のメッセージを比較する
            $expectedMessage,
            $actualMessages[\array_search($expectedMessage, $actualMessages, true)]
        );
    }

    public function testログイン機能_入力間違いエラー()
    {
        $response = $this->get('/login'); // ログインページを開く
        $response->assertStatus(200);

        $requestParams = [
            'email' => 'abcd@example.com',
            'password' => "password1",
        ];

        $request = new LoginRequest(); // インスタンスを生成
        $rules = $request->rules(); // バリデーションルールを取得

        /** @var \Illuminate\Validation\Validator */
        $validator = Validator::make($requestParams, $rules); // ダミーデータをバリデーションに通す

        $actualMessages = $validator->messages()->get('failed'); // 実際のバリデーションメッセージを取得
        $expectedMessage = 'ログイン情報が登録されていません'; // 期待するバリデーションメッセージ
        $response = $this->get('/mypage/profile'); // 認証済みのみ入れるページへ遷移
        $response->assertStatus(302);

        $this->assertSame( // 期待するメッセージと実際のメッセージを比較する
            $expectedMessage,
            $actualMessages[\array_search($expectedMessage, $actualMessages, true)]
        );
    }

    public function testログイン機能_ログイン成功()
    {
        $response = $this->get('/login'); // 会員登録ページを開く
        $response->assertStatus(200);

        // テスト用ユーザーを作成
        $user = User::factory([
            'name' => 'testuser',
            'email' => 'abcd@example.com',
            'password' => 'password',
        ])->create();

        // ログインを実行
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302);
        // リダイレクトで帰ってきた時のパス
        $response->assertRedirect('/login');
        // このユーザーがログイン認証されているか
        $this->assertAuthenticated();
    }
}
