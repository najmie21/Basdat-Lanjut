<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-sm font-extrabold text-slate-900">Daftar Pengguna Mahasiswa</h1>
            <p class="mt-1 text-xs text-slate-500">Direktori akun mahasiswa yang terdaftar pada sistem. Admin dapat menghapus akun anggota yang lupa kata sandi agar NIM dan email dapat digunakan kembali.</p>
        </div>
        <form action="{{ route('items.index') }}" method="GET" class="flex w-full gap-2 sm:max-w-sm">
            <input type="hidden" name="admin_tab" value="users">
            <label for="user_search" class="sr-only">Cari mahasiswa</label>
            <input id="user_search" name="user_search" type="search" value="{{ request('user_search') }}" placeholder="Cari nama, email, atau NIM" class="min-w-0 flex-1 rounded-lg border border-slate-200 px-3 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none">
            <button type="submit" class="rounded-lg bg-slate-800 px-4 py-2 text-xs font-bold text-white transition-colors hover:bg-slate-700">Cari</button>
        </form>
    </div>
    <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200">
        <table class="w-full min-w-[900px] text-left text-sm">
            <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-4 py-3 font-bold">Nama</th>
                    <th class="px-4 py-3 font-bold">Email</th>
                    <th class="px-4 py-3 font-bold">NIM</th>
                    <th class="px-4 py-3 font-bold">Terdaftar</th>
                    <th class="px-4 py-3 font-bold">Status akun</th>
                    <th class="px-4 py-3 text-right font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($managedUsers as $managedUser)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-slate-800">{{ $managedUser->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $managedUser->email }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $managedUser->nim ?: 'Belum diisi' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $managedUser->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-xs"><span class="rounded-full px-2 py-1 font-bold {{ $managedUser->must_change_password ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">{{ $managedUser->must_change_password ? 'Wajib password baru' : 'Aktif' }}</span></td>
                        <td class="px-4 py-3 text-right">
                            <button type="button" onclick="openDeleteUserModal('{{ route('admin.users.destroy', $managedUser) }}', '{{ e($managedUser->name) }}', '{{ e($managedUser->nim ?? '-') }}')" class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-bold text-rose-700 transition-colors hover:bg-rose-100 cursor-pointer">Hapus Anggota</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">Tidak ada mahasiswa yang cocok dengan pencarian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($managedUsers->hasPages())
        <div class="mt-4">{{ $managedUsers->links() }}</div>
    @endif
</div>
