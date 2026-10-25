<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Admin - Lost & Found FT UNTIRTA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 bg-grid-pattern">
    <x-navbar-admin admin-tab="admins" />

    <main class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:ml-64 lg:px-8">
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800" role="status">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800" role="alert">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert">
                <p class="font-bold">Periksa kembali data yang dimasukkan.</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5">
                <h1 class="text-xl font-extrabold text-slate-900">Tambah Admin</h1>
                <p class="mt-1 text-sm text-slate-500">Password minimal 12 karakter. Admin baru diminta mengganti password saat pertama kali masuk.</p>
            </div>
            <form action="{{ route('admin.admins.store') }}" method="POST" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @csrf
                <div>
                    <label for="new_name" class="mb-1 block text-xs font-bold text-slate-700">Nama</label>
                    <input id="new_name" name="name" value="{{ old('name') }}" required maxlength="255" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                </div>
                <div>
                    <label for="new_email" class="mb-1 block text-xs font-bold text-slate-700">Email</label>
                    <input id="new_email" name="email" type="email" value="{{ old('email') }}" required maxlength="255" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                </div>
                <div>
                    <label for="new_password" class="mb-1 block text-xs font-bold text-slate-700">Password</label>
                    <input id="new_password" name="password" type="password" required minlength="12" autocomplete="new-password" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                </div>
                <div>
                    <label for="new_password_confirmation" class="mb-1 block text-xs font-bold text-slate-700">Konfirmasi password</label>
                    <input id="new_password_confirmation" name="password_confirmation" type="password" required minlength="12" autocomplete="new-password" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                </div>
                <div class="sm:col-span-2 lg:col-span-4">
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-indigo-700">Tambah akun admin</button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5">
                <h2 class="text-xl font-extrabold text-slate-900">Daftar Admin</h2>
                <p class="mt-1 text-sm text-slate-500">Penghapusan ditolak bila akun masih memiliki laporan, klaim, komentar, atau merupakan admin terakhir.</p>
            </div>
            <div class="space-y-4">
                @forelse($admins as $admin)
                    <article class="rounded-xl border border-slate-200 p-4">
                        <form action="{{ route('admin.admins.update', $admin) }}" method="POST" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="name_{{ $admin->id }}" class="mb-1 block text-xs font-bold text-slate-700">Nama</label>
                                <input id="name_{{ $admin->id }}" name="name" value="{{ $admin->name }}" required maxlength="255" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                            </div>
                            <div>
                                <label for="email_{{ $admin->id }}" class="mb-1 block text-xs font-bold text-slate-700">Email</label>
                                <input id="email_{{ $admin->id }}" name="email" type="email" value="{{ $admin->email }}" required maxlength="255" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                            </div>
                            <div>
                                <label for="password_{{ $admin->id }}" class="mb-1 block text-xs font-bold text-slate-700">Password baru (opsional)</label>
                                <input id="password_{{ $admin->id }}" name="password" type="password" minlength="12" autocomplete="new-password" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                            </div>
                            <div>
                                <label for="password_confirmation_{{ $admin->id }}" class="mb-1 block text-xs font-bold text-slate-700">Konfirmasi password</label>
                                <input id="password_confirmation_{{ $admin->id }}" name="password_confirmation" type="password" minlength="12" autocomplete="new-password" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                            </div>
                            <div class="flex flex-wrap items-end gap-2">
                                <button type="submit" class="rounded-lg bg-indigo-600 px-3 py-2 text-xs font-bold text-white transition-colors hover:bg-indigo-700">Simpan</button>
                                @if(!auth()->user()->is($admin) && $admins->total() > 1)
                                    <button type="submit" form="delete-admin-{{ $admin->id }}" onclick="return confirm('Hapus akun admin ini?')" class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-bold text-rose-700 transition-colors hover:bg-rose-100">Hapus</button>
                                @endif
                            </div>
                        </form>
                        @if(!auth()->user()->is($admin) && $admins->total() > 1)
                            <form id="delete-admin-{{ $admin->id }}" action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </article>
                @empty
                    <p class="rounded-xl bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">Belum ada akun admin.</p>
                @endforelse
            </div>
            @if($admins->hasPages())
                <div class="mt-5">{{ $admins->links() }}</div>
            @endif
        </section>
    </main>
</body>
</html>
