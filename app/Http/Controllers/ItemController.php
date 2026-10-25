<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use App\Http\Requests\StoreItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Controller Lost and Found Kampus FT UNTIRTA.
 *
 * Fitur utama:
 * - Mahasiswa dapat melaporkan barang hilang/temuan.
 * - Mahasiswa dapat mengajukan klaim barang temuan dengan bukti kepemilikan.
 * - Admin memverifikasi atau menolak klaim.
 * - Klaim dapat dibatalkan/unclaim oleh admin atau pengaju klaim.
 */
class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['user', 'claimant', 'verifiedBy']);
        $user = auth()->user();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%')
                    ->orWhere('kategori', 'like', '%' . $search . '%')
                    ->orWhere('lokasi', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('claim_status')) {
            $query->where('claim_status', $request->input('claim_status'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $user?->role === 'mahasiswa' ? $user->id : $request->input('user_id'));
        }

        if ($request->filled('claimant_id')) {
            $query->where('claimant_id', $user?->role === 'mahasiswa' ? $user->id : $request->input('claimant_id'));
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }

        // Handle sorting
        $sort = $request->input('sort', 'terbaru');
        switch ($sort) {
            case 'terlama':
                $query->orderBy('created_at', 'asc');
                break;
            case 'nama_asc':
                $query->orderBy('nama_barang', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('nama_barang', 'desc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('created_at', 'desc');
        }

        $items = $query->paginate(9)->withQueryString();

        $isAdmin = auth()->user()?->role === 'admin';
        if ($isAdmin && ! $request->query->has('admin_tab')) {
            return redirect()->route('admin.dashboard');
        }

        $adminTab = $isAdmin ? $request->query('admin_tab', 'items') : null;
        if ($isAdmin && $adminTab === 'dashboard') {
            return redirect()->route('admin.dashboard');
        }
        if ($isAdmin && ! in_array($adminTab, ['users', 'items'], true)) {
            $adminTab = 'items';
        }

        $managedUsers = null;
        $dashboardStats = null;
        if ($isAdmin && $adminTab === 'users') {
            $usersQuery = User::query()->where('role', 'mahasiswa');

            if ($request->filled('user_search')) {
                $userSearch = trim($request->input('user_search'));
                $usersQuery->where(function ($userQuery) use ($userSearch) {
                    $userQuery->where('name', 'like', '%' . $userSearch . '%')
                        ->orWhere('email', 'like', '%' . $userSearch . '%')
                        ->orWhere('nim', 'like', '%' . $userSearch . '%');
                });
            }

            $managedUsers = $usersQuery
                ->orderBy('name')
                ->paginate(10, ['id', 'name', 'email', 'nim', 'created_at', 'must_change_password'], 'users_page')
                ->withQueryString();
        }

        if ($user?->role === 'mahasiswa') {
            $dashboardStats = [
                'laporan_saya' => Item::where('user_id', $user->id)->count(),
                'claim_saya' => Item::where('claimant_id', $user->id)->count(),
                'claim_menunggu' => Item::where('claimant_id', $user->id)->where('claim_status', 'menunggu_verifikasi')->count(),
                'claim_terverifikasi' => Item::where('claimant_id', $user->id)->where('claim_status', 'terverifikasi')->count(),
            ];
        }

        return view('items.index', compact('items', 'managedUsers', 'dashboardStats', 'isAdmin', 'adminTab'));
    }

    public function adminDashboard()
    {
        abort_unless(auth()->user()?->role === 'admin', 403);

        $dashboardStats = [
            'total_laporan' => Item::count(),
            'barang_ditemukan' => Item::where('status', 'ditemukan')->count(),
            'menunggu_verifikasi' => Item::where('claim_status', 'menunggu_verifikasi')->count(),
            'selesai_diklaim' => Item::where('status', 'diklaim')->count(),
            'kategori' => Item::query()
                ->selectRaw('kategori, COUNT(*) as total')
                ->groupBy('kategori')
                ->orderByDesc('total')
                ->get(),
        ];

        return view('admin.dashboard', compact('dashboardStats'));
    }

    public function store(StoreItemRequest $request)
    {
        $validatedData = $request->validated();
        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = now()->format('YmdHis') . '-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/items');

            if (! is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $fileName);
            $fotoPath = 'uploads/items/' . $fileName;
        }

        Item::create([
            'user_id' => auth()->id(),
            'nama_barang' => $validatedData['nama_barang'],
            'kategori' => $validatedData['kategori'],
            'lokasi' => $validatedData['lokasi'],
            'tanggal_temu' => $validatedData['tanggal_temu'],
            'deskripsi' => $validatedData['deskripsi'],
            'status' => $validatedData['status'],
            'penemu' => $validatedData['status'] === 'ditemukan' ? $validatedData['penemu'] : null,
            'penanganan' => $validatedData['status'] === 'ditemukan' ? $validatedData['penanganan'] : null,
            'foto_path' => $fotoPath,
        ]);

        return redirect()->route('items.index')->with('success', 'Laporan barang berhasil disimpan.');
    }

    /**
     * Update status manual. Dipakai untuk laporan hilang yang sudah ditemukan kembali.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:hilang,ditemukan,diklaim'],
        ], [
            'status.in' => 'Status tidak valid.',
        ]);

        $item = Item::findOrFail($id);
        $user = auth()->user();

        if ($user->role !== 'admin' && $item->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah status barang ini.');
        }

        $item->status = $request->input('status');

        if ($item->status !== 'diklaim') {
            $this->resetClaim($item);
        }

        $item->save();

        return redirect()->back()->with('success', 'Status barang berhasil diperbarui.');
    }

    /**
     * Mahasiswa mengajukan klaim untuk barang temuan.
     * Klaim belum langsung diterima; admin harus memverifikasi dulu.
     */
    public function claim(Request $request, Item $item)
    {
        $request->validate([
            'claim_message' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'claim_message.required' => 'Bukti kepemilikan wajib diisi.',
            'claim_message.min' => 'Bukti kepemilikan minimal 10 karakter agar admin bisa memverifikasi.',
        ]);

        if ($item->status !== 'ditemukan') {
            return redirect()->back()->withErrors(['claim_message' => 'Barang ini tidak bisa diklaim karena statusnya bukan ditemukan.']);
        }

        if ($item->claim_status === 'menunggu_verifikasi') {
            return redirect()->back()->withErrors(['claim_message' => 'Barang ini sudah sedang menunggu verifikasi klaim.']);
        }

        if ($item->claim_status === 'terverifikasi') {
            return redirect()->back()->withErrors(['claim_message' => 'Barang ini sudah diklaim dan terverifikasi.']);
        }

        if ($item->user_id === auth()->id()) {
            return redirect()->back()->withErrors(['claim_message' => 'Kamu tidak perlu mengklaim laporan yang kamu buat sendiri.']);
        }

        $item->claimant_id = auth()->id();
        $item->claim_message = $request->input('claim_message');
        $item->claim_status = 'menunggu_verifikasi';
        $item->claimed_at = now();
        $item->verified_by = null;
        $item->verified_at = null;
        $item->save();

        return redirect()->back()->with('success', 'Klaim berhasil diajukan. Tunggu verifikasi admin.');
    }

    /**
     * Membatalkan klaim/unclaim.
     * Bisa dilakukan oleh admin atau mahasiswa yang mengajukan klaim.
     */
    public function unclaim(Item $item)
    {
        $user = auth()->user();

        if ($user->role !== 'admin' && $item->claimant_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk membatalkan klaim ini.');
        }

        $this->resetClaim($item);
        $item->status = 'ditemukan';
        $item->save();

        return redirect()->back()->with('success', 'Klaim barang berhasil dibatalkan.');
    }

    /**
     * Admin memverifikasi klaim setelah bukti kepemilikan dirasa valid.
     */
    public function verifyClaim(Item $item)
    {
        $this->ensureAdmin();

        if ($item->claim_status !== 'menunggu_verifikasi' || ! $item->claimant_id) {
            return redirect()->back()->withErrors(['claim_message' => 'Tidak ada klaim yang sedang menunggu verifikasi.']);
        }

        $item->claim_status = 'terverifikasi';
        $item->status = 'diklaim';
        $item->verified_by = auth()->id();
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Klaim berhasil diverifikasi. Status barang menjadi diklaim.');
    }

    /**
     * Admin menolak klaim jika bukti pemilik tidak cocok.
     */
    public function rejectClaim(Item $item)
    {
        $this->ensureAdmin();

        $this->resetClaim($item);
        $item->claim_status = 'ditolak';
        $item->status = 'ditemukan';
        $item->verified_by = auth()->id();
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Klaim ditolak. Barang kembali tersedia untuk diklaim.');
    }

    public function show(Item $item)
    {
        $item->load(['user', 'claimant', 'verifiedBy', 'comments.user']);

        return view('items.show', compact('item'));
    }

    public function comment(Request $request, Item $item)
    {
        $request->validate([
            'message' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'message.required' => 'Komentar tidak boleh kosong.',
            'message.min' => 'Komentar minimal 5 karakter.',
        ]);

        Comment::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'message' => $request->input('message'),
        ]);

        return redirect()->route('items.show', $item->id)->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'nim' => [
                $user->role === 'mahasiswa' ? 'required' : 'nullable',
                'string',
                'regex:/^[0-9]{8,15}$/',
                'unique:users,nim,' . $user->id,
            ],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'nim.required' => 'NIM wajib diisi untuk akun mahasiswa.',
            'nim.regex' => 'NIM harus terdiri dari 8 sampai 15 angka.',
            'nim.unique' => 'NIM sudah digunakan oleh akun lain.',
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($user->role === 'mahasiswa') {
            $user->nim = $request->input('nim');
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return redirect()->route('items.index')->with('success', 'Profil berhasil diperbarui.');
    }

    private function resetClaim(Item $item): void
    {
        $item->claimant_id = null;
        $item->claim_message = null;
        $item->claim_status = 'belum_diklaim';
        $item->claimed_at = null;
        $item->verified_by = null;
        $item->verified_at = null;
    }

    private function ensureAdmin(): void
    {
        if (auth()->user()?->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat memverifikasi klaim.');
        }
    }

    /**
     * Menghapus laporan barang (hanya admin atau reporter barang).
     */
    public function destroy(Item $item)
    {
        // Hanya admin atau reporter yang dapat menghapus
        if (auth()->user()->role !== 'admin' && $item->user_id !== auth()->user()->id) {
            abort(403, 'Anda tidak berhak menghapus barang ini.');
        }

        if ($item->foto_path) {
            $filePath = public_path($item->foto_path);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }
}
