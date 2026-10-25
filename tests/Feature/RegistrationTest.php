<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Buat Akun Baru');
        $response->assertSee('aria-label="Logo Lost and Found"', false);
        $response->assertDontSee('name="role"', false);
        $response->assertDontSee('<option value="admin"', false);
        $response->assertSee('id="nim-field"', false);
        $response->assertSee('placeholder="nama@gmail.com"', false);
        $response->assertDontSee('@untirta.ac.id');
        $response->assertSee('data-password-toggle="password"', false);
        $response->assertSee('data-password-toggle="password_confirmation"', false);
    }

    public function test_login_screen_does_not_render_quick_login_shortcuts(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('aria-label="Logo Lost and Found"', false);
        $response->assertDontSee('Quick Login');
        $response->assertSee('placeholder="nama@gmail.com"', false);
        $response->assertDontSee('@untirta.ac.id');
        $response->assertSee('data-password-toggle="password"', false);
    }

    public function test_new_users_can_register_as_mahasiswa(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Mahasiswa',
            'email' => 'mahasiswa@gmail.com',
            'nim' => '3337240001',
            'role' => 'mahasiswa',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('items.index'));
        
        $this->assertDatabaseHas('users', [
            'name' => 'Test Mahasiswa',
            'email' => 'mahasiswa@gmail.com',
            'nim' => '3337240001',
            'role' => 'mahasiswa',
        ]);
    }

    public function test_public_registration_ignores_a_submitted_admin_role(): void
    {
        $this->post('/register', [
            'name' => 'Test Mahasiswa',
            'email' => 'mahasiswa@gmail.com',
            'nim' => '3337240010',
            'role' => 'admin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('items.index'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Test Mahasiswa',
            'email' => 'mahasiswa@gmail.com',
            'nim' => '3337240010',
            'role' => 'mahasiswa',
        ]);
    }

    public function test_student_registration_requires_a_nim(): void
    {
        $this->post('/register', [
            'name' => 'Test Mahasiswa',
            'email' => 'mahasiswa@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('nim');
    }

    public function test_login_redirects_users_to_their_role_homepage(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password123'),
        ]);

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ])->assertRedirect(route('admin.dashboard'));

        auth()->logout();

        $student = User::factory()->create([
            'role' => 'mahasiswa',
            'email' => 'student@gmail.com',
            'password' => bcrypt('password123'),
        ]);

        $this->post('/login', [
            'email' => $student->email,
            'password' => 'password123',
        ])->assertRedirect(route('items.index'));
    }

    public function test_registration_validation_fails_on_duplicate_email(): void
    {
        User::create([
            'name' => 'Existing User',
            'email' => 'duplicate@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'mahasiswa',
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'duplicate@gmail.com',
            'nim' => '3337240003',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_new_registration_rejects_untirta_email_addresses(): void
    {
        $this->post('/register', [
            'name' => 'Test Mahasiswa',
            'email' => 'mahasiswa@untirta.ac.id',
            'nim' => '3337240004',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');
    }
}
