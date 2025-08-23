<?php

namespace Tests\Feature;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    // 1.会員登録機能
    public function test会員登録機能_名前未入力エラー()
    {
        $response = $this->get('/register'); // 会員登録ページを開く
        $response->assertStatus(200);

        $requestParams = [
            'name' => null,
            'email' => 'abcd@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $request = new RegisterRequest(); // インスタンスを生成
        $rules = $request->rules(); // バリデーションルールを取得

        /** @var \Illuminate\Validation\Validator */
        $validator = Validator::make($requestParams, $rules); // ダミーデータをバリデーションに通す

        $actualMessages = $validator->messages()->get('name'); // 実際のバリデーションメッセージを取得
        $expectedMessage = 'お名前を入力してください'; // 期待するバリデーションメッセージ
        $response = $this->get('/mypage/profile'); // 登録ボタン押下
        $response->assertStatus(302);

        $this->assertSame( // 期待するメッセージと実際のメッセージを比較する
            $expectedMessage,
            $actualMessages[\array_search($expectedMessage, $actualMessages, true)]
        );
    }

    public function test会員登録機能_メールアドレス未入力エラー()
    {
        $response = $this->get('/register'); // 会員登録ページを開く
        $response->assertStatus(200);

        $requestParams = [
            'name' => 'testuser',
            'email' => null,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $request = new RegisterRequest(); // インスタンスを生成
        $rules = $request->rules(); // バリデーションルールを取得

        /** @var \Illuminate\Validation\Validator */
        $validator = Validator::make($requestParams, $rules); // ダミーデータをバリデーションに通す

        $actualMessages = $validator->messages()->get('email'); // 実際のバリデーションメッセージを取得
        $expectedMessage = 'メールアドレスを入力してください'; // 期待するバリデーションメッセージ
        $response = $this->get('/mypage/profile'); // 登録ボタン押下
        $response->assertStatus(302);

        $this->assertSame( // 期待するメッセージと実際のメッセージを比較する
            $expectedMessage,
            $actualMessages[\array_search($expectedMessage, $actualMessages, true)]
        );
    }

    public function test会員登録機能_パスワード未入力エラー()
    {
        $response = $this->get('/register'); // 会員登録ページを開く
        $response->assertStatus(200);

        $requestParams = [
            'name' => 'testuser',
            'email' => 'abcd@example.com',
            'password' => "",
            'password_confirmation' => "",
        ];

        $request = new RegisterRequest(); // インスタンスを生成
        $rules = $request->rules(); // バリデーションルールを取得

        /** @var \Illuminate\Validation\Validator */
        $validator = Validator::make($requestParams, $rules); // ダミーデータをバリデーションに通す

        $actualMessages = $validator->messages()->get('password'); // 実際のバリデーションメッセージを取得
        $expectedMessage = 'パスワードを入力してください'; // 期待するバリデーションメッセージ
        $response = $this->get('/mypage/profile'); // 登録ボタン押下
        $response->assertStatus(302);

        $this->assertSame( // 期待するメッセージと実際のメッセージを比較する
            $expectedMessage,
            $actualMessages[\array_search($expectedMessage, $actualMessages, true)]
        );
    }

    public function test会員登録機能_パスワード7文字以下エラー()
    {
        $response = $this->get('/register'); // 会員登録ページを開く
        $response->assertStatus(200);

        $requestParams = [
            'name' => 'testuser',
            'email' => 'abcd@example.com',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ];

        $request = new RegisterRequest(); // インスタンスを生成
        $rules = $request->rules(); // バリデーションルールを取得

        /** @var \Illuminate\Validation\Validator */
        $validator = Validator::make($requestParams, $rules); // ダミーデータをバリデーションに通す

        $actualMessages = $validator->messages()->get('password'); // 実際のバリデーションメッセージを取得
        $expectedMessage = 'パスワードは8文字以上で入力してください'; // 期待するバリデーションメッセージ
        $response = $this->get('/mypage/profile'); // 登録ボタン押下
        $response->assertStatus(302);

        $this->assertSame( // 期待するメッセージと実際のメッセージを比較する
            $expectedMessage,
            $actualMessages[\array_search($expectedMessage, $actualMessages, true)]
        );
    }

    public function test会員登録機能_確認パスワード不一致エラー()
    {
        $response = $this->get('/register'); // 会員登録ページを開く
        $response->assertStatus(200);

        $requestParams = [
            'name' => 'testuser',
            'email' => 'abcd@example.com',
            'password' => 'password',
            'password_confirmation' => 'password1',
        ];

        $request = new RegisterRequest(); // インスタンスを生成
        $rules = $request->rules(); // バリデーションルールを取得

        /** @var \Illuminate\Validation\Validator */
        $validator = Validator::make($requestParams, $rules); // ダミーデータをバリデーションに通す

        $actualMessages = $validator->messages()->get('password'); // 実際のバリデーションメッセージを取得
        $expectedMessage = 'パスワードと一致しません'; // 期待するバリデーションメッセージ
        $response = $this->get('/mypage/profile'); // 登録ボタン押下
        $response->assertStatus(302);

        $this->assertSame( // 期待するメッセージと実際のメッセージを比較する
            $expectedMessage,
            $actualMessages[\array_search($expectedMessage, $actualMessages, true)]
        );
    }

    public function test会員登録機能_新規登録_ログイン成功()
    {
        $response = $this->get('/register'); // 会員登録ページを開く
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

        // ログイン
        $this->actingAs($user);

        // テスト対象のURLにアクセスさせる
        $response = $this->get('/mypage/profile'); // 登録ボタン押下
        $response->assertStatus(200);
    }
}
