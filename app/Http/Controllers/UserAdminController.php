<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class UserAdminController extends Controller
{
    public function indexAdmins(): View
    {
        $admins = User::query()
            ->where('role', 'admin')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.admins', compact('admins'));
    }

    public function storeAdmin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ])->forceFill(['must_change_password' => true])->save();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Akun admin berhasil ditambahkan.');
    }

    public function updateAdmin(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'admin', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:12', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (filled($validated['password'] ?? null)) {
            $user->password = Hash::make($validated['password']);
            $user->must_change_password = true;
        }
        $user->save();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroyAdmin(User $user): RedirectResponse
    {
        abort_unless($user->role === 'admin', 404);
        abort_if($user->is(auth()->user()), 403);

        $result = DB::transaction(function () use ($user): string {
            $admins = User::query()
                ->where('role', 'admin')
                ->lockForUpdate()
                ->get(['id']);

            if ($admins->count() <= 1) {
                return 'last_admin';
            }

            $hasItemReferences = Item::query()
                ->where('user_id', $user->id)
                ->orWhere('claimant_id', $user->id)
                ->orWhere('verified_by', $user->id)
                ->exists();

            if ($hasItemReferences || Comment::query()->where('user_id', $user->id)->exists()) {
                return 'has_records';
            }

            $user->delete();

            return 'deleted';
        });

        if ($result === 'last_admin') {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Admin terakhir yang masih aktif tidak dapat dihapus.');
        }

        if ($result === 'has_records') {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Akun ini masih terkait laporan, klaim, atau komentar sehingga tidak dapat dihapus.');
        }

        return redirect()->route('admin.admins.index')
            ->with('success', 'Akun admin berhasil dihapus.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless($user->role === 'mahasiswa', 404);
        abort_if($user->is(auth()->user()), 403);

        $name = $user->name;
        $nim = $user->nim;

        try {
            DB::transaction(function () use ($user) {
                try {
                    Password::broker()->deleteToken($user);
                } catch (Throwable) {
                    // Abaikan bila tabel token atau token tidak ada.
                }
                $user->delete();
            });
        } catch (Throwable $e) {
            return redirect()->route('items.index', ['admin_tab' => 'users'])
                ->with('error', 'Gagal menghapus akun pengguna: '.$e->getMessage());
        }

        $nimInfo = $nim ? " (NIM: {$nim})" : '';

        return redirect()->route('items.index', ['admin_tab' => 'users'])
            ->with('success', "Akun anggota '{$name}'{$nimInfo} berhasil dihapus. Email dan NIM akun tersebut kini dapat didaftarkan kembali.");
    }
}
