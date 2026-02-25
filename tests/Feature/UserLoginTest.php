<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        // إنشاء مستخدم جديد
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'), // تأكد من تشفير كلمة المرور
        ]);

        // محاكاة إرسال طلب تسجيل الدخول
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // تأكد من إعادة التوجيه بعد تسجيل الدخول (عادةً إلى /home)
        $response->assertRedirect('/home');

        // تأكد من أن المستخدم قد تم تسجيل دخوله
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        // إنشاء مستخدم جديد
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // محاكاة إرسال طلب تسجيل الدخول مع بيانات غير صحيحة
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // تأكد من أن المستخدم لم يتمكن من الدخول
        $response->assertSessionHasErrors('email');
    }
}
