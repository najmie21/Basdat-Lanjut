<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_a_student_and_release_email_and_nim(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create([
            'role' => 'mahasiswa',
            'email' => 'old.account@gmail.com',
            'nim' => '3337240188',
        ]);
        $item = Item::create([
            'user_id' => $student->id,
            'nama_barang' => 'Barang mahasiswa',
            'kategori' => 'Lainnya',
            'lokasi' => 'Kampus',
            'tanggal_temu' => now()->toDateString(),
            'deskripsi' => 'Laporan yang terhubung ke akun mahasiswa.',
            'status' => 'ditemukan',
        ]);
        $comment = Comment::create([
            'item_id' => $item->id,
            'user_id' => $student->id,
            'message' => 'Komentar akun mahasiswa.',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $student))
            ->assertRedirect(route('items.index', ['admin_tab' => 'users']))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $student->id]);
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);

        $replacement = User::factory()->create([
            'role' => 'mahasiswa',
            'email' => 'old.account@gmail.com',
            'nim' => '3337240188',
        ]);

        $this->assertDatabaseHas('users', ['id' => $replacement->id, 'nim' => '3337240188']);
    }

    public function test_student_cannot_delete_another_student(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $otherStudent = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($student)
            ->delete(route('admin.users.destroy', $otherStudent))
            ->assertForbidden();
    }

    public function test_admin_cannot_delete_another_admin_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $otherAdmin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $otherAdmin))
            ->assertNotFound();
    }

    public function test_admin_can_create_update_and_list_admin_accounts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.admins.index'))
            ->assertOk()
            ->assertSee('Daftar Admin');

        $this->actingAs($admin)
            ->post(route('admin.admins.store'), [
                'name' => 'Admin Baru',
                'email' => 'admin.baru@example.com',
                'password' => 'strong-admin-password',
                'password_confirmation' => 'strong-admin-password',
            ])
            ->assertRedirect(route('admin.admins.index'));

        $newAdmin = User::where('email', 'admin.baru@example.com')->firstOrFail();
        $this->assertSame('admin', $newAdmin->role);
        $this->assertTrue(Hash::check('strong-admin-password', $newAdmin->password));
        $this->assertTrue($newAdmin->must_change_password);

        $this->patch(route('admin.admins.update', $newAdmin), [
            'name' => 'Admin Diperbarui',
            'email' => 'admin.updated@example.com',
            'password' => 'a-different-admin-password',
            'password_confirmation' => 'a-different-admin-password',
        ])->assertRedirect(route('admin.admins.index'));

        $newAdmin->refresh();
        $this->assertSame('Admin Diperbarui', $newAdmin->name);
        $this->assertSame('admin.updated@example.com', $newAdmin->email);
        $this->assertTrue(Hash::check('a-different-admin-password', $newAdmin->password));

        $this->delete(route('admin.admins.destroy', $newAdmin))
            ->assertRedirect(route('admin.admins.index'))
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $newAdmin->id]);
    }

    public function test_students_cannot_access_admin_management_routes(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($student)
            ->get(route('admin.admins.index'))
            ->assertForbidden();

        $this->actingAs($student)
            ->post(route('admin.admins.store'), [
                'name' => 'Admin Ilegal',
                'email' => 'illegal@example.com',
                'password' => 'strong-admin-password',
                'password_confirmation' => 'strong-admin-password',
            ])
            ->assertForbidden();
    }

    public function test_admin_cannot_delete_self_or_an_account_with_item_records(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $otherAdmin = User::factory()->create(['role' => 'admin']);
        $item = Item::create([
            'user_id' => $otherAdmin->id,
            'nama_barang' => 'Laporan admin',
            'kategori' => 'Lainnya',
            'lokasi' => 'Kampus',
            'tanggal_temu' => now()->toDateString(),
            'deskripsi' => 'Data yang tidak boleh terhapus bersama akun admin.',
            'status' => 'ditemukan',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.admins.destroy', $admin))
            ->assertForbidden();

        $this->delete(route('admin.admins.destroy', $otherAdmin))
            ->assertRedirect(route('admin.admins.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $otherAdmin->id, 'role' => 'admin']);
        $this->assertDatabaseHas('items', ['id' => $item->id]);
    }

    public function test_database_seeder_is_idempotent_and_preserves_existing_data(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $item = Item::create([
            'user_id' => $student->id,
            'nama_barang' => 'Laporan tersimpan',
            'kategori' => 'Lainnya',
            'lokasi' => 'Kampus',
            'tanggal_temu' => now()->toDateString(),
            'deskripsi' => 'Laporan yang harus tetap ada setelah seeding.',
            'status' => 'ditemukan',
        ]);

        $accounts = collect(range(1, 3))->map(fn (int $number) => [
            'name' => "Admin {$number}",
            'email' => "admin{$number}@example.com",
            'password' => "admin-strong-password-{$number}",
        ])->all();
        config(['admin.accounts' => $accounts]);

        $this->seed(DatabaseSeeder::class);
        $firstAdmin = User::where('email', $accounts[0]['email'])->firstOrFail();
        $initialHash = $firstAdmin->password;

        config(['admin.accounts.0.password' => 'changed-strong-password']);
        $this->seed(DatabaseSeeder::class);

        $this->assertSame($initialHash, $firstAdmin->fresh()->password);
        $this->assertSame(3, User::where('role', 'admin')->count());
        $this->assertDatabaseHas('users', ['id' => $student->id]);
        $this->assertDatabaseHas('items', ['id' => $item->id]);
        $this->assertTrue(Hash::check($accounts[0]['password'], $initialHash));
    }
}