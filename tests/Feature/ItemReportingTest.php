<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemReportingTest extends TestCase
{
    use RefreshDatabase;

    public function test_found_item_records_finder_and_current_handling(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('items.store'), [
            'nama_barang' => 'Kunci Motor',
            'kategori' => 'Kunci',
            'lokasi' => 'Kantin FT',
            'tanggal_temu' => now()->toDateString(),
            'deskripsi' => 'Kunci motor dengan gantungan merah.',
            'status' => 'ditemukan',
            'penemu' => 'Dina',
            'penanganan' => 'diserahkan_satpam',
        ]);

        $response->assertRedirect(route('items.index'));
        $this->assertDatabaseHas('items', [
            'nama_barang' => 'Kunci Motor',
            'penemu' => 'Dina',
            'penanganan' => 'diserahkan_satpam',
        ]);

        $item = Item::where('nama_barang', 'Kunci Motor')->firstOrFail();
        $this->get(route('items.show', $item))
            ->assertOk()
            ->assertSee('aria-label="Logo Lost and Found"', false)
            ->assertSee('Dina')
            ->assertSee('Dititipkan ke satpam');
    }

    public function test_found_item_requires_finder_and_handling_details(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('items.store'), [
            'nama_barang' => 'Kunci Motor',
            'kategori' => 'Kunci',
            'lokasi' => 'Kantin FT',
            'tanggal_temu' => now()->toDateString(),
            'deskripsi' => 'Kunci motor dengan gantungan merah.',
            'status' => 'ditemukan',
        ]);

        $response->assertSessionHasErrors(['penemu', 'penanganan']);
    }

    public function test_dashboard_content_depends_on_user_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('aria-label="Logo Lost and Found"', false)
            ->assertSee('Dashboard Admin')
            ->assertSee('Buka antrean verifikasi')
            ->assertSee('Laporan menurut Kategori')
            ->assertSee('Daftar Pengguna')
            ->assertSee('Daftar Barang')
            ->assertSee('action="' . route('logout') . '"', false)
            ->assertSee(route('items.index', ['admin_tab' => 'users']), false);

        $this->actingAs($admin)
            ->get(route('items.index', ['admin_tab' => 'users']))
            ->assertOk()
            ->assertSee('Daftar Pengguna Mahasiswa')
            ->assertSee($student->email)
            ->assertSee($student->nim)
            ->assertDontSee('Laporan menurut Kategori')
            ->assertDontSee('Daftar Barang (');

        $this->actingAs($admin)
            ->get(route('items.index', ['admin_tab' => 'items']))
            ->assertOk()
            ->assertSee('Daftar Barang (')
            ->assertDontSee('Laporan menurut Kategori');

        $this->actingAs($student)
            ->get(route('items.index'))
            ->assertOk()
            ->assertSee('aria-label="Logo Lost and Found"', false)
            ->assertSee('Ringkasan Akun / Aktivitas Mahasiswa')
            ->assertSee('Ringkasan laporan dan klaim yang kamu ajukan, ' . $student->name . '.')
            ->assertSee('Cari atau laporkan barang')
            ->assertSee('Daftar Barang (')
            ->assertDontSee('Dashboard Admin');
    }

    public function test_dashboards_are_restricted_to_their_roles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($student)->get(route('admin.dashboard'))->assertForbidden();
        auth()->logout();
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get('/mahasiswa/dashboard')->assertNotFound();
    }

    public function test_admin_home_route_opens_the_separate_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('items.index'))
            ->assertRedirect(route('admin.dashboard'));

        $this->get(route('items.index', ['admin_tab' => 'dashboard']))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_guest_homepage_still_renders_the_lost_and_found_homepage(): void
    {
        $this->get(route('items.index'))
            ->assertOk()
            ->assertSee('Kehilangan atau Menemukan Barang di Fakultas Teknik?')
            ->assertSee('Temukan atau Laporkan Barang')
            ->assertSee('aria-label="Logo Lost and Found"', false);
    }

    public function test_student_summary_counts_are_based_on_the_authenticated_user(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $otherStudent = User::factory()->create(['role' => 'mahasiswa']);

        foreach ([
            ['user_id' => $student->id, 'claimant_id' => null, 'claim_status' => 'belum_diklaim'],
            ['user_id' => $student->id, 'claimant_id' => $otherStudent->id, 'claim_status' => 'menunggu_verifikasi'],
            ['user_id' => $otherStudent->id, 'claimant_id' => $student->id, 'claim_status' => 'menunggu_verifikasi'],
            ['user_id' => $otherStudent->id, 'claimant_id' => $student->id, 'claim_status' => 'terverifikasi'],
            ['user_id' => $otherStudent->id, 'claimant_id' => $otherStudent->id, 'claim_status' => 'menunggu_verifikasi'],
        ] as $index => $attributes) {
            Item::create(array_merge([
                'nama_barang' => 'Barang uji ' . $index,
                'kategori' => 'Lainnya',
                'lokasi' => 'Kampus FT',
                'tanggal_temu' => now()->toDateString(),
                'deskripsi' => 'Barang untuk verifikasi statistik akun.',
                'status' => 'ditemukan',
            ], $attributes));
        }

        $response = $this->actingAs($student)
            ->get(route('items.index', [
                'user_id' => $otherStudent->id,
                'claimant_id' => $otherStudent->id,
            ]))
            ->assertOk()
            ->assertSee('Ringkasan Akun / Aktivitas Mahasiswa')
            ->assertSee(route('items.index', ['user_id' => $student->id]), false);

        preg_match_all('/<p class="mt-1 text-2xl font-extrabold [^"]+">(\d+)<\\/p>/', $response->getContent(), $matches);
        $this->assertSame(['2', '2', '1', '1'], array_slice($matches[1], 0, 4));

        $this->actingAs($student)
            ->get(route('items.index', ['user_id' => $otherStudent->id]))
            ->assertSee('Barang uji 0')
            ->assertDontSee('Barang uji 4');
    }

    public function test_admin_dashboard_shows_report_totals_for_each_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $reporter = User::factory()->create();

        foreach (['Elektronik', 'Elektronik', 'Kunci'] as $category) {
            Item::create([
                'user_id' => $reporter->id,
                'nama_barang' => 'Barang ' . $category,
                'kategori' => $category,
                'lokasi' => 'Kampus FT',
                'tanggal_temu' => now()->toDateString(),
                'deskripsi' => 'Laporan barang untuk pengujian kategori.',
                'status' => 'ditemukan',
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Elektronik')
            ->assertSee('2 laporan')
            ->assertSee('Kunci')
            ->assertSee('1 laporan');

        $this->actingAs($admin)
            ->get(route('items.index', ['admin_tab' => 'items', 'kategori' => 'Kunci']))
            ->assertOk()
            ->assertSee('Barang Kunci')
            ->assertDontSee('Barang Elektronik');
    }
}