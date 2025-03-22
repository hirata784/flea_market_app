<?php

namespace Tests\Feature;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_seed()
    {
        $this->seed(UsersTableSeeder::class);

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'name' => 'test name 1',
            'email' => 'taro@example.com',
        ]);
    }

    // public function testログイン_名前未入力エラー()
    // {
    //     $response = $this->get('/login'); // ログインページを開く
    //     $response->assertStatus(200);

    //     $requestParams = [
    //         'email' => null,
    //         'password' => 'password',
    //     ];

    //     $request = new LoginRequest(); // インスタンスを生成
    //     $rules = $request->rules(); // バリデーションルールを取得

    //     /** @var \Illuminate\Validation\Validator */
    //     $validator = Validator::make($requestParams, $rules); // ダミーデータをバリデーションに通す

    //     $actualMessages = $validator->messages()->get('email'); // 実際のバリデーションメッセージを取得
    //     $expectedMessage = 'メールアドレスを入力してください'; // 期待するバリデーションメッセージ
    //     $response = $this->get('/mypage/profile'); // 認証済みのみ入れるページへ遷移
    //     $response->assertStatus(302);

    //     $this->assertSame( // 期待するメッセージと実際のメッセージを比較する
    //         $expectedMessage,
    //         $actualMessages[\array_search($expectedMessage, $actualMessages, true)]
    //     );
    // }

    //     public function testログイン_パスワード未入力エラー()
    //     {
    //         $response = $this->get('/login'); // ログインページを開く
    //         $response->assertStatus(200);

    //         $requestParams = [
    //             'email' => 'abcd@example.com',
    //             'password' => "",
    //         ];

    //         $request = new LoginRequest(); // インスタンスを生成
    //         $rules = $request->rules(); // バリデーションルールを取得

    //         /** @var \Illuminate\Validation\Validator */
    //         $validator = Validator::make($requestParams, $rules); // ダミーデータをバリデーションに通す

    //         $actualMessages = $validator->messages()->get('password'); // 実際のバリデーションメッセージを取得
    //         $expectedMessage = 'パスワードを入力してください'; // 期待するバリデーションメッセージ
    //         $response = $this->get('/mypage/profile'); // 認証済みのみ入れるページへ遷移
    //         $response->assertStatus(302);

    //         $this->assertSame( // 期待するメッセージと実際のメッセージを比較する
    //             $expectedMessage,
    //             $actualMessages[\array_search($expectedMessage, $actualMessages, true)]
    //         );
    //     }


    //     public function testログイン_入力間違いエラー()
    //     {
    //         $response = $this->get('/login'); // ログインページを開く
    //         $response->assertStatus(200);

    //         // テスト用ユーザーを作成
    //         $user = User::factory([
    //             'name' => 'testuser',
    //             'email' => 'abcd@example.com',
    //             'password' => 'password',
    //             'nickname' => "",
    //             'post_code' => "",
    //             'address' => "",
    //             'building' => "",
    //             'profile_img' => "",
    //         ])->create();
    //         $response = $this->get('/login');
    //         $response->assertStatus(200);

    //         // ログインする
    //         $response = $this->post(route('login'), ['email' => $this->$user->email, 'password' => 'Test1234']);
    //         // リダイレクトでページ遷移してくるのでstatusは302
    //         $response->assertStatus(302);
    //         // リダイレクトで帰ってきた時のパス
    //         $response->assertRedirect('/');
    //         // このユーザーがログイン認証されているか
    //         $this->assertAuthenticatedAs($this->$user);
    //     }
}
