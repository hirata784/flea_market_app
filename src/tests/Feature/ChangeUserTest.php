<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangeUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function testユーザー情報変更_初期値設定()
    {
        // ユーザー作成
        $user = User::factory()->create();
        // ユーザーにログイン後、プロフィール編集画面を開く
        $response =  $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);

        // データベースにデータが存在するかをチェック
        // (プロフィール画像、ユーザー名、郵便番号、住所）
        $this->assertDatabaseHas('users', [
            'profile_img' =>  $user['profile_img'],
            'nickname' =>  $user['nickname'],
            'post_code' => $user['post_code'],
            'address' => $user['address'],
            'building' => $user['building'],
        ]);

        // 画面上に各項目の初期値が表示されているかテスト
        $response->assertSee($user['profile_img']);
        $response->assertSee($user['nickname']);
        $response->assertSee($user['post_code']);
        $response->assertSee($user['address']);
        $response->assertSee($user['building']);
    }
}
