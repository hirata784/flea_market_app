<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function testログアウト_ログアウト成功()
    {
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
        $response = $this->get('/mypage/profile'); // 認証済みのみ入れるページへ遷移
        $response->assertStatus(200);         //ログインしていることを確認

        $response = $this->post('/logout');
        $this->assertGuest();         //ログアウトしていることを確認
    }
}