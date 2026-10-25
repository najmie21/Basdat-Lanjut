<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - Lost & Found FT UNTIRTA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 bg-grid-pattern">
    <x-navbar-admin />

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:ml-64 lg:px-8">
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800" role="status">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800" role="alert">{{ session('error') }}</div>
        @endif

        <section class="mb-6 rounded-2xl bg-gradient-to-br from-indigo-900 via-indigo-800 to-slate-900 p-6 text-white shadow-lg sm:p-8">
            <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">Panel administrasi</p>
            <h1 class="mt-2 text-2xl font-extrabold tracking-tight sm:text-3xl">Dashboard Admin</h1>
            <p class="mt-2 max-w-2xl text-sm text-indigo-100">Pantau seluruh laporan dan proses klaim yang menunggu verifikasi.</p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('items.index', ['admin_tab' => 'users']) }}" class="rounded-lg border border-white/20 bg-white/10 px-4 py-2 text-xs font-bold text-white transition-colors hover:bg-white/20">Kelola Pengguna</a>
                <a href="{{ route('admin.admins.index') }}" class="rounded-lg border border-white/20 bg-white/10 px-4 py-2 text-xs font-bold text-white transition-colors hover:bg-white/20">Kelola Admin</a>
                <a href="{{ route('items.index', ['admin_tab' => 'items', 'claim_status' => 'menunggu_verifikasi']) }}" class="rounded-lg bg-white px-4 py-2 text-xs font-bold text-indigo-800 transition-colors hover:bg-indigo-50">Buka antrean verifikasi</a>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm sm:p-6">
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                <a href="{{ route('items.index', ['admin_tab' => 'items']) }}" class="block rounded-xl border border-indigo-200 bg-indigo-50/50 p-4 transition-colors hover:bg-indigo-50">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total</p>
                    <p class="mt-1 text-2xl font-extrabold text-slate-900">{{ $dashboardStats['total_laporan'] }}</p>
                </a>
                <a href="{{ route('items.index', ['admin_tab' => 'items', 'status' => 'ditemukan']) }}" class="block rounded-xl border border-amber-200 bg-amber-50/50 p-4 transition-colors hover:bg-amber-50">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Ditemukan</p>
                    <p class="mt-1 text-2xl font-extrabold text-amber-700">{{ $dashboardStats['barang_ditemukan'] }}</p>
                </a>
                <a href="{{ route('items.index', ['admin_tab' => 'items', 'claim_status' => 'menunggu_verifikasi']) }}" class="block rounded-xl border border-indigo-200 bg-indigo-50/50 p-4 transition-colors hover:bg-indigo-50">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-indigo-600">Verifikasi</p>
                    <p class="mt-1 text-2xl font-extrabold text-indigo-700">{{ $dashboardStats['menunggu_verifikasi'] }}</p>
                </a>
                <a href="{{ route('items.index', ['admin_tab' => 'items', 'status' => 'diklaim']) }}" class="block rounded-xl border border-emerald-200 bg-emerald-50/50 p-4 transition-colors hover:bg-emerald-50">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Diklaim</p>
                    <p class="mt-1 text-2xl font-extrabold text-emerald-700">{{ $dashboardStats['selesai_diklaim'] }}</p>
                </a>
            </div>

            <div class="mt-6 border-t border-slate-200 pt-5">
                <div class="mb-4 flex items-end justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-extrabold text-slate-900">Laporan menurut Kategori</h2>
                        <p class="mt-1 text-xs text-slate-500">Distribusi seluruh laporan barang di kampus.</p>
                    </div>
                    <span class="text-xs font-semibold text-slate-500">{{ $dashboardStats['total_laporan'] }} laporan</span>
                </div>
                @if($dashboardStats['kategori']->isNotEmpty())
                    @php($maxCategoryCount = max(1, $dashboardStats['kategori']->max('total')))
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 md:grid-cols-2">
                        @foreach($dashboardStats['kategori'] as $category)
                            @php($barColor = ['bg-indigo-500', 'bg-emerald-500', 'bg-amber-500', 'bg-rose-500'][$loop->index % 4])
                            <a href="{{ route('items.index', ['admin_tab' => 'items', 'kategori' => $category->kategori]) }}" aria-label="Lihat laporan kategori {{ $category->kategori }}" class="-mx-2 block rounded-lg p-2 transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <div class="mb-1.5 flex items-center justify-between gap-3">
                                    <span class="truncate text-xs font-semibold text-slate-700">{{ $category->kategori }}</span>
                                    <span class="shrink-0 text-xs font-bold text-slate-900">{{ $category->total }} laporan</span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-slate-100" role="img" aria-label="{{ $category->kategori }}: {{ $category->total }} laporan">
                                    <div class="h-full rounded-full {{ $barColor }}" style="width: {{ ($category->total / $maxCategoryCount) * 100 }}%"></div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-500">Belum ada laporan untuk ditampilkan.</p>
                @endif
            </div>
        </section>
    </main>
</body>
</html>
