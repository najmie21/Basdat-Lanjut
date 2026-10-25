<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PasswordRecoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_opens_the_email_and_nim_popup(): void
    {
        $this->get(route('password.request'))
            ->assertRedirect(route('login', ['show_forgot_password' => 1]));

        $this->get(route('login', ['show_forgot_password' => 1]))
            ->assertOk()
            ->assertSee('Lupa kata sandi?')
            ->assertSee('forgotPasswordModal')
            ->assertSee('name="nim"', false)
            ->assertSee('placeholder="nama@gmail.com"', false)
            ->assertDontSee('@untirta.ac.id');
    }

    public function test_reset_email_is_not_sent_when_email_and_nim_do_not_match(): void
    {
        config(['mail.default' => 'smtp']);
        Notification::fake();
        $student = User::factory()->create(['role' => 'mahasiswa', 'nim' => '3337240101']);

        $response = $this->post(route('password.email'), [
            'email' => $student->email,
            'nim' => '3337249999',
        ]);

        $response->assertRedirect(route('login', ['show_forgot_password' => 1]));
        $response->assertSessionHas('password_reset_status');
        Notification::assertNothingSent();
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $student->email]);
    }

    public function test_log_mailer_does_not_claim_that_an_inbox_email_was_sent(): void
    {
        config(['mail.default' => 'log']);
        Notification::fake();
        $student = User::factory()->create(['role' => 'mahasiswa']);

        $response = $this->post(route('password.email'), [
            'email' => $student->email,
            'nim' => $student->nim,
        ]);

        $response->assertSessionHas('password_reset_status', 'Layanan email belum dikonfigurasi untuk mengirim ke inbox. Hubungi administrator aplikasi.');
        Notification::assertNothingSent();
    }

    public function test_missing_reset_token_table_returns_to_popup_instead_of_showing_database_error(): void
    {
        config(['mail.default' => 'smtp']);
        Notification::fake();
        $student = User::factory()->create(['role' => 'mahasiswa']);
        Schema::drop('password_reset_tokens');

        $this->post(route('password.email'), [
            'email' => $student->email,
            'nim' => $student->nim,
        ])
            ->assertRedirect(route('login', ['show_forgot_password' => 1]))
            ->assertSessionHas('password_reset_status', 'Email verifikasi belum dapat diproses saat ini. Silakan coba kembali beberapa saat lagi.');

        Notification::assertNothingSent();
    }

    public function test_matching_email_and_nim_send_a_reset_notification_with_a_secure_link(): void
    {
        config(['mail.default' => 'smtp']);
        Notification::fake();
        $student = User::factory()->create(['role' => 'mahasiswa', 'nim' => '3337240102']);

        $response = $this->post(route('password.email'), [
            'email' => $student->email,
            'nim' => $student->nim,
        ]);

        $response->assertRedirect(route('login', ['show_forgot_password' => 1]));
        Notification::assertSentTo($student, PasswordResetNotification::class);

        $notification = Notification::sent($student, PasswordResetNotification::class)->first();
        $resetUrl = route('password.reset', ['token' => $notification->token, 'email' => $student->email]);

        $this->assertStringContainsString('/reset-password/', $resetUrl);
        $this->assertStringStartsWith(config('app.url'), $resetUrl);
        $this->get($resetUrl)
            ->assertOk()
            ->assertSee('Atur password baru')
            ->assertSee('data-password-toggle="password"', false)
            ->assertSee('data-password-toggle="password_confirmation"', false);
    }

    public function test_reset_token_updates_password_and_cannot_be_reused(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $token = Password::createToken($student);
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $student->email]);

        $this->get($resetUrl)->assertOk();

        $payload = [
            'token' => $token,
            'email' => $student->email,
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ];

        $this->post(route('password.update'), $payload)
            ->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('new-secure-password', $student->fresh()->password));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $student->email]);

        $this->post(route('password.update'), $payload)->assertSessionHasErrors('email');
    }

    public function test_admin_user_directory_shows_nim_without_whatsapp_reset_controls(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create([
            'role' => 'mahasiswa',
            'nim' => '3337240111',
        ]);

        $this->actingAs($admin)
            ->get(route('items.index', ['admin_tab' => 'users']))
            ->assertOk()
            ->assertSee($student->email)
            ->assertSee($student->nim)
            ->assertSee('Direktori akun mahasiswa yang terdaftar pada sistem.')
            ->assertSee('Hapus Anggota')
            ->assertSee('deleteUserModal')
            ->assertDontSee('Buat sandi sementara')
            ->assertDontSee('WhatsApp: 628');
    }

    public function test_student_can_complete_their_nim_in_profile(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa', 'nim' => null]);

        $this->actingAs($student)
            ->patch(route('profile.update'), [
                'name' => $student->name,
                'email' => $student->email,
                'nim' => '3337240123',
            ])
            ->assertRedirect(route('items.index'));

        $this->assertDatabaseHas('users', [
            'id' => $student->id,
            'nim' => '3337240123',
        ]);
    }

    public function test_temporary_password_is_consumed_once_and_requires_a_new_password(): void
    {
        $temporaryPassword = 'legacy-one-time-password';
        $student = User::factory()->create([
            'role' => 'mahasiswa',
            'must_change_password' => true,
            'password' => $temporaryPassword,
        ]);
        $otherStudent = User::factory()->create(['role' => 'mahasiswa']);

        $this->post(route('login'), [
            'email' => $otherStudent->email,
            'password' => $temporaryPassword,
        ])->assertSessionHasErrors('email');

        $loginResponse = $this->post(route('login'), [
            'email' => $student->email,
            'password' => $temporaryPassword,
        ]);

        $loginResponse->assertRedirect(route('password.change'));
        $this->assertAuthenticatedAs($student);
        $this->assertFalse(Hash::check($temporaryPassword, $student->fresh()->password));
        $this->assertTrue($student->fresh()->must_change_password);

        $this->get(route('items.index'))->assertRedirect(route('password.change'));

        $this->post(route('password.change.update'), [
            'password' => 'my-new-secure-password',
            'password_confirmation' => 'my-new-secure-password',
        ])->assertRedirect(route('items.index'));

        $this->assertFalse($student->fresh()->must_change_password);
        $this->post(route('logout'));

        $this->post(route('login'), [
            'email' => $student->email,
            'password' => $temporaryPassword,
        ])->assertSessionHasErrors('email');

        $this->post(route('login'), [
            'email' => $student->email,
            'password' => 'my-new-secure-password',
        ])->assertRedirect(route('items.index'));
        $this->assertAuthenticatedAs($student);
    }

    public function test_failed_login_redirects_cleanly_without_reopening_forgot_password_modal(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@untirta.ac.id',
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->from(route('login', ['show_forgot_password' => 1]))
            ->post(route('login'), [
                'email' => 'admin@untirta.ac.id',
                'password' => 'wrong-password',
            ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('id="forgotPasswordModal" class="fixed inset-0 z-50 hidden', false);
    }
}