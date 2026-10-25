<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lost & Found FT UNTIRTA - Portal Laporan Barang Hilang & Temuan</title>
    
    <!-- Import Google Fonts (Plus Jakarta Sans) untuk estetika tipografi modern -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Memuat aset CSS & JS menggunakan Vite compiler bawaan Laravel -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Efek transisi halus */
        .transition-all-300 {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col bg-grid-pattern">
    @php($isAdmin = auth()->check() && auth()->user()->role === 'admin')

    @if($isAdmin)
        <x-navbar-admin :admin-tab="$adminTab" />
    @else
    <!-- HEADER / NAVBAR -->
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200/80 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & Judul Aplikasi -->
                <div class="flex min-w-0 items-center space-x-2 sm:space-x-3">
                    <x-logo class="h-10 w-10 shrink-0 sm:h-11 sm:w-11" />
                    <div>
                        <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-none">Lost & Found FT</h1>
                        <span class="text-xs font-semibold text-indigo-600 tracking-wider uppercase">UNTIRTA</span>
                    </div>
                </div>

                <!-- Tombol Laporkan Barang Baru & Info Autentikasi -->
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->role === 'mahasiswa')
                            <a href="#account-summary" class="hidden text-xs font-bold text-indigo-700 hover:text-indigo-900 sm:inline">Ringkasan Saya</a>
                        @endif
                        <button onclick="openModal()" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-xl transition-all-300 shadow-md shadow-indigo-100 hover:shadow-lg hover:-translate-y-0.5 focus:outline-none cursor-pointer">
                            <!-- Icon Plus (SVG) -->
                            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                            </svg>
                            Laporkan Barang
                        </button>
                        <div class="flex items-center space-x-3 border-l border-slate-200 pl-4">
                            <div class="flex flex-col text-right">
                                <button type="button" onclick="openProfileModal()" title="Edit Profile" class="text-xs font-bold text-slate-800 leading-none hover:text-indigo-600 transition-colors cursor-pointer">{{ auth()->user()->name }}</button>
                                <span class="text-[9px] font-extrabold mt-1 px-2 py-0.5 rounded-md uppercase tracking-wider leading-none self-end {{ auth()->user()->role === 'admin' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200' }}">
                                    {{ auth()->user()->role }}
                                </span>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" title="Keluar" onclick="event.stopPropagation();" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 active:bg-slate-200 transition-colors focus:outline-none cursor-pointer">
                                    <!-- Ikon keluar (SVG) -->
                                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 active:bg-indigo-200 rounded-xl transition-all-300 border border-indigo-200/50 shadow-sm focus:outline-none">
                            <!-- Ikon masuk (SVG) -->
                            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3.5-3l-3-3m3 3l-3 3m3-3H9"></path>
                            </svg>
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    @endif

    @if(session('success') || session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full {{ $isAdmin ? 'lg:ml-64' : '' }}">
            @if(session('success'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if(!$isAdmin)
    <!-- HERO SECTION -->
    <section class="relative py-12 bg-gradient-to-br from-indigo-900 via-indigo-850 to-slate-900 text-white overflow-hidden shadow-inner {{ $isAdmin ? 'lg:ml-64' : '' }}">
        <!-- Dekorasi Latar Belakang -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-indigo-300 via-slate-950 to-black"></div>
        <div class="absolute -top-24 -left-20 w-80 h-80 bg-indigo-500 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute -bottom-24 -right-20 w-80 h-80 bg-pink-500 rounded-full blur-3xl opacity-20"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center md:text-left">
            <div class="md:flex md:items-center md:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-block px-3.5 py-1.5 text-xs font-semibold uppercase tracking-widest bg-indigo-500/20 text-indigo-300 rounded-full mb-4 border border-indigo-500/30">
                        Universitas Sultan Ageng Tirtayasa
                    </span>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight mb-4 leading-tight">
                        Kehilangan atau Menemukan Barang di Fakultas Teknik?
                    </h2>
                    <p class="text-base md:text-lg text-slate-300 font-medium">
                        Mari bantu sesama civitas akademika FT Untirta mengembalikan barang berharga mereka. Laporkan barang temuan Anda atau cari barang Anda yang hilang di sekitar kampus Cilegon secara terpusat.
                    </p>
                </div>

            </div>
        </div>
    </section>
    @endif

    @auth
        @if(auth()->user()->role === 'mahasiswa')
            <section id="account-summary" class="border-b border-slate-200/80 bg-white">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-sm font-extrabold text-slate-900">Ringkasan Akun / Aktivitas Mahasiswa</p>
                            <p class="mt-1 text-xs text-slate-500">Ringkasan laporan dan klaim yang kamu ajukan, {{ auth()->user()->name }}.</p>
                        </div>
                        <a href="#item-search" class="inline-flex w-fit items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition-colors hover:bg-indigo-700">Cari atau laporkan barang</a>
                    </div>
                    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                        <a href="{{ route('items.index', ['user_id' => auth()->id()]) }}" class="block rounded-xl border p-3 transition-all duration-200 hover:bg-slate-100/50 hover:shadow-sm {{ request('user_id') == auth()->id() ? 'border-indigo-600 ring-2 ring-indigo-100 bg-indigo-50/30' : 'border-slate-200 bg-slate-50' }}">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Laporan</p>
                            <p class="mt-1 text-2xl font-extrabold text-slate-900">{{ $dashboardStats['laporan_saya'] }}</p>
                        </a>
                        <a href="{{ route('items.index', ['claimant_id' => auth()->id()]) }}" class="block rounded-xl border p-3 transition-all duration-200 hover:bg-emerald-100/30 hover:shadow-sm {{ request('claimant_id') == auth()->id() && !request()->has('claim_status') ? 'border-emerald-600 ring-2 ring-emerald-100 bg-emerald-50/30' : 'border-slate-200 bg-slate-50' }}">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Klaim</p>
                            <p class="mt-1 text-2xl font-extrabold text-emerald-700">{{ $dashboardStats['claim_saya'] }}</p>
                        </a>
                        <a href="{{ route('items.index', ['claimant_id' => auth()->id(), 'claim_status' => 'menunggu_verifikasi']) }}" class="block rounded-xl border p-3 transition-all duration-200 hover:bg-indigo-100/30 hover:shadow-sm {{ request('claimant_id') == auth()->id() && request('claim_status') === 'menunggu_verifikasi' ? 'border-indigo-600 ring-2 ring-indigo-100 bg-indigo-50/30' : 'border-slate-200 bg-slate-50' }}">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-indigo-600">Menunggu</p>
                            <p class="mt-1 text-2xl font-extrabold text-indigo-700">{{ $dashboardStats['claim_menunggu'] }}</p>
                        </a>
                        <a href="{{ route('items.index', ['claimant_id' => auth()->id(), 'claim_status' => 'terverifikasi']) }}" class="block rounded-xl border p-3 transition-all duration-200 hover:bg-teal-100/30 hover:shadow-sm {{ request('claimant_id') == auth()->id() && request('claim_status') === 'terverifikasi' ? 'border-teal-600 ring-2 ring-teal-100 bg-teal-50/30' : 'border-slate-200 bg-slate-50' }}">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-teal-600">Terverifikasi</p>
                            <p class="mt-1 text-2xl font-extrabold text-teal-700">{{ $dashboardStats['claim_terverifikasi'] }}</p>
                        </a>
                    </div>
                </div>
            </section>
        @endif
    @endauth

    @if($isAdmin && $adminTab === 'users')
        <section class="border-b border-slate-200/80 bg-white {{ $isAdmin ? 'lg:ml-64' : '' }}">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                @include('admin.users')
            </div>
        </section>
    @endif

    @guest
        <section class="border-b border-slate-200/80 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="mb-3 text-2xl font-bold text-slate-900">Temukan atau Laporkan Barang</h2>
                    <p class="mb-6 text-sm text-slate-600">Untuk melaporkan barang temuan, mengajukan klaim, atau mengelola laporan, silakan masuk terlebih dahulu.</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-indigo-700">Masuk</a>
                </div>
            </div>
        </section>
    @endguest

    <!-- MAIN CONTENT (SEARCH, FILTER & CARDS) - HANYA UNTUK AUTH USERS -->
    @auth
    @if(!$isAdmin || $adminTab === 'items')
    <main id="item-search" class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full {{ $isAdmin ? 'lg:ml-64' : '' }}">

        <!-- PANEL PENCARIAN LANGSUNG -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 mb-8 {{ $isAdmin ? 'mt-0' : '-mt-14' }} relative z-20">
            <form action="{{ route('items.index') }}" method="GET" class="relative">
                @if($isAdmin)
                    <input type="hidden" name="admin_tab" value="items">
                @endif
                <label for="search" class="sr-only">Cari Barang</label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-grow flex items-center">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama barang atau ciri khas..." class="w-full pl-12 pr-4 py-3 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all duration-200 text-sm font-medium text-slate-800 placeholder-slate-400 shadow-sm">
                    </div>
                    <div class="flex gap-2">
                        @if(request()->filled('search'))
                            <a href="{{ route('items.index', $isAdmin ? ['admin_tab' => 'items'] : []) }}" class="px-4 py-2.5 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                                Reset
                            </a>
                        @endif
                        <button type="submit" class="px-6 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-lg transition-colors shadow-md shadow-indigo-150 cursor-pointer whitespace-nowrap">
                            Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- GRID DAFTAR BARANG -->
        <div>
            <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                <h3 class="text-xl font-bold text-slate-900 flex items-center">
                    <svg class="w-5.5 h-5.5 mr-2 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                    Daftar Barang ({{ $items->total() }})
                </h3>
                <div class="flex items-center space-x-2 flex-wrap gap-2">
                    @if(request()->anyFilled(['status', 'claim_status', 'user_id', 'claimant_id', 'kategori']))
                        <span class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg border border-indigo-150 shadow-sm text-xs font-semibold">
                            Filter Aktif
                            <a href="{{ route('items.index', $isAdmin ? ['admin_tab' => 'items'] : []) }}" title="Hapus Filter" class="ml-2 text-indigo-500 hover:text-indigo-800 font-bold cursor-pointer">✕</a>
                        </span>
                    @endif
                    <select onchange="window.location.href = this.value;" class="px-3 py-1.5 text-xs font-semibold border border-slate-200 rounded-lg bg-white hover:border-slate-300 transition-colors cursor-pointer text-slate-700">
                        <option value="{{ route('items.index', array_merge(request()->query(), ['sort' => 'terbaru'])) }}" {{ request('sort', 'terbaru') === 'terbaru' ? 'selected' : '' }}>↓ Terbaru</option>
                        <option value="{{ route('items.index', array_merge(request()->query(), ['sort' => 'terlama'])) }}" {{ request('sort') === 'terlama' ? 'selected' : '' }}>↑ Terlama</option>
                        <option value="{{ route('items.index', array_merge(request()->query(), ['sort' => 'nama_asc'])) }}" {{ request('sort') === 'nama_asc' ? 'selected' : '' }}>A → Z</option>
                        <option value="{{ route('items.index', array_merge(request()->query(), ['sort' => 'nama_desc'])) }}" {{ request('sort') === 'nama_desc' ? 'selected' : '' }}>Z → A</option>
                    </select>
                </div>
            </div>

            <!-- Jika barang tidak ditemukan -->
            @if($items->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center max-w-lg mx-auto shadow-sm">
                    <div class="w-20 h-20 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v4.5A2.25 2.25 0 002.25 13.5zm0 0l6-6M9 9h.008v.008H9V9zm3 0h.008v.008H12V9zm3 0h.008v.008H15V9z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 mb-1">Barang tidak ditemukan</h4>
                    <p class="text-sm text-slate-500 mb-6">
                        Maaf, tidak ada laporan barang yang sesuai dengan kriteria pencarian Anda. Coba ganti kata kunci atau pilih kategori lain.
                    </p>
                    <a href="{{ route('items.index', $isAdmin ? ['admin_tab' => 'items'] : []) }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition-all duration-200">
                        Bersihkan Pencarian
                    </a>
                </div>
            @else
                <!-- Grid Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($items as $item)
                        <!-- Card Item -->
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col group hover:shadow-lg hover:border-slate-300/80 transition-all duration-300 cursor-pointer" onclick="cardNavigate(event, this, '{{ route('items.show', $item->id) }}')">
                            <!-- Visual/Foto Barang -->
                            <div class="relative h-48 bg-slate-100 overflow-hidden flex-shrink-0">
                                @if($item->foto_path)
                                    <img src="{{ str_starts_with($item->foto_path, 'uploads/') ? asset($item->foto_path) : asset('storage/' . $item->foto_path) }}" alt="{{ $item->nama_barang }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                                @else
                                    <!-- Placeholder Foto Eksklusif dengan Gradient -->
                                    <div class="w-full h-full bg-gradient-to-br from-indigo-50 to-indigo-100 flex flex-col items-center justify-center text-indigo-400">
                                        <svg class="w-12 h-12 mb-2 opacity-80" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path>
                                        </svg>
                                        <span class="text-xs font-semibold uppercase tracking-widest text-indigo-500/70">Tidak Ada Foto</span>
                                    </div>
                                @endif

                                <!-- Badge Status -->
                                <div class="absolute top-4 left-4">
                                    @if($item->status == 'hilang')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-500 text-white border border-rose-600 shadow-md">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white mr-1.5 animate-pulse"></span>
                                            HILANG
                                        </span>
                                    @elseif($item->status == 'ditemukan')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-500 text-white border border-amber-600 shadow-md">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white mr-1.5"></span>
                                            DITEMUKAN
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white border border-emerald-600 shadow-md">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                            </svg>
                                            DIKLAIM
                                        </span>
                                    @endif
                                </div>

                                <!-- Badge Kategori di atas foto kanan -->
                                <div class="absolute top-4 right-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-black/60 text-white backdrop-blur-sm">
                                        {{ $item->kategori }}
                                    </span>
                                </div>
                            </div>

                            <!-- Detail Content Card -->
                            <div class="p-5 flex-grow flex flex-col justify-between">
                                <div>
                                    <!-- Baris Atas Info: Tanggal & Lokasi -->
                                    <div class="flex items-center justify-between text-slate-400 text-xs font-semibold mb-2.5">
                                        <span class="flex items-center">
                                            <!-- Icon Calendar -->
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"></path>
                                            </svg>
                                            {{ $item->tanggal_temu->format('d M Y') }}
                                        </span>
                                        <span class="flex items-center max-w-[150px] truncate" title="{{ $item->lokasi }}">
                                            <!-- Icon Location Pin -->
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"></path>
                                            </svg>
                                            {{ $item->lokasi }}
                                        </span>
                                    </div>

                                    <!-- Nama Barang -->
                                    <h4 class="text-base font-bold text-slate-900 mb-2 leading-snug group-hover:text-indigo-600 transition-colors duration-200">
                                        {{ $item->nama_barang }}
                                    </h4>

                                    <!-- Deskripsi Barang -->
                                    <p class="text-xs text-slate-500 line-clamp-3 mb-4 leading-relaxed font-medium">
                                        {{ $item->deskripsi }}
                                    </p>
                                    @if($item->status === 'ditemukan')
                                        <p class="text-xs text-slate-600 mb-4">
                                            <span class="font-bold">Penemu:</span> {{ $item->penemu ?: 'Belum dicatat' }}
                                            <span class="text-slate-400">&middot;</span>
                                            {{ $item->penanganan === 'diserahkan_satpam' ? 'Dititipkan ke satpam' : ($item->penanganan === 'dibawa_penemu' ? 'Dibawa penemu' : 'Status penyimpanan belum dicatat') }}
                                        </p>
                                    @endif
                                    @if($item->claim_status === 'menunggu_verifikasi')
                                        <div class="mb-4 rounded-xl border border-indigo-150 bg-indigo-50/50 p-3.5 shadow-sm text-left">
                                            <p class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-wider flex items-center">
                                                <span class="inline-flex w-2 h-2 rounded-full bg-indigo-600 mr-2 animate-pulse"></span>
                                                Menunggu Verifikasi Admin
                                            </p>
                                            <div class="mt-2 space-y-1.5 text-xs text-slate-700 font-medium">
                                                <p class="flex justify-between items-center">
                                                    <span class="text-slate-400">Pengaju:</span>
                                                    <span class="font-bold text-slate-800">Mahasiswa</span>
                                                </p>
                                                @if(auth()->check() && auth()->user()->role === 'admin')
                                                    <p class="flex justify-between items-center border-b border-indigo-100/50 pb-1.5">
                                                        <span class="text-slate-400">Email:</span>
                                                        <span class="font-bold text-slate-800">{{ $item->claimant->email ?? '-' }}</span>
                                                    </p>
                                                    <div class="mt-2">
                                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1 flex items-center">
                                                            📄 Bukti Kepemilikan:
                                                        </span>
                                                        <div class="bg-white border border-indigo-150 rounded-lg p-2.5 text-xs text-slate-600 leading-relaxed font-normal whitespace-pre-line shadow-inner max-h-36 overflow-y-auto hover:border-indigo-300 transition-colors">
                                                            {{ $item->claim_message }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif($item->claim_status === 'terverifikasi')
                                        <div class="mb-4 rounded-xl border border-emerald-100 bg-emerald-50 px-3 py-2">
                                            <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider">Klaim Terverifikasi</p>
                                            <p class="text-xs text-slate-600 mt-1">Pemilik: <span class="font-bold">{{ $item->claimant->name ?? 'Mahasiswa' }}</span></p>
                                        </div>
                                    @elseif($item->claim_status === 'ditolak')
                                        <div class="mb-4 rounded-xl border border-rose-100 bg-rose-50 px-3 py-2">
                                            <p class="text-[10px] font-extrabold text-rose-600 uppercase tracking-wider">Klaim Terakhir Ditolak</p>
                                            <p class="text-xs text-slate-500 mt-1">Barang masih bisa diklaim ulang dengan bukti yang lebih jelas.</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Bagian Bawah: Pelapor & Tombol Aksi -->
                                <div class="pt-4 border-t border-slate-100 mt-auto">
                                    <div class="flex items-center justify-between">
                                        <!-- Nama Pelapor -->
                                        <div class="flex items-center space-x-2">
                                            <div class="w-7 h-7 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 text-[10px] font-bold border border-slate-300">
                                                {{ strtoupper(substr($item->user->name ?? 'U', 0, 2)) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider leading-none">Pelapor</span>
                                                <span class="text-xs font-semibold text-slate-700 truncate max-w-[90px] leading-tight mt-0.5" title="{{ $item->user->name ?? 'Civitas Akademika FT Untirta' }}">
                                                    {{ $item->user->name ?? 'Demo User' }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Aksi Klaim / Verifikasi -->
                                        <div class="min-w-[150px] text-right">
                                            @auth
                                                @if(auth()->user()->role === 'admin')
                                                    @if($item->claim_status === 'menunggu_verifikasi')
                                                        <div class="flex flex-col gap-2 items-end">
                                                            <form action="{{ route('items.claim.verify', $item->id) }}" method="POST" onsubmit="return confirm('Verifikasi klaim ini? Pastikan bukti kepemilikan sudah cocok.');">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-lg transition-all duration-200 cursor-pointer shadow-md shadow-emerald-100 hover:shadow-lg">
                                                                    ✓ Verifikasi
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('items.claim.reject', $item->id) }}" method="POST" onsubmit="return confirm('Tolak klaim ini?');">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 active:bg-rose-200 border border-rose-200 rounded-lg transition-all duration-200 cursor-pointer shadow-sm hover:shadow">
                                                                    ✕ Tolak
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @elseif($item->status === 'diklaim')
                                                        <form action="{{ route('items.unclaim', $item->id) }}" method="POST" onsubmit="return confirm('Batalkan klaim barang ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition-all duration-200 cursor-pointer">
                                                                Batalkan Klaim
                                                            </button>
                                                        </form>
                                                    @else
                                                        <details class="text-left group">
                                                            <summary class="inline-flex items-center px-3 py-1.5 text-[10px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg transition-all duration-200 hover:bg-indigo-100 cursor-pointer list-none">
                                                                👁 Pantau Klaim
                                                                <svg class="w-3 h-3 ml-1 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                                            </summary>
                                                            <div class="absolute mt-1 bg-white border border-slate-200 rounded-lg shadow-md p-2 z-10 min-w-max">
                                                                @if(auth()->user()->role === 'admin' || $item->user_id === auth()->user()->id)
                                                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini? Tindakan ini tidak dapat dibatalkan.');" class="block" onclick="event.stopPropagation();">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="w-full text-left px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded transition-colors" onclick="event.stopPropagation();">
                                                                            🗑 Hapus Barang
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </details>
                                                    @endif
                                                @else
                                                    @if($item->status === 'ditemukan')
                                                        @if($item->claim_status === 'menunggu_verifikasi' && $item->claimant_id === auth()->id())
                                                            <form action="{{ route('items.unclaim', $item->id) }}" method="POST" onsubmit="return confirm('Batalkan klaim kamu?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition-all duration-200 cursor-pointer">
                                                                    Batalkan Klaim
                                                                </button>
                                                            </form>
                                                        @elseif($item->claim_status === 'menunggu_verifikasi')
                                                            <span class="inline-flex text-[10px] font-bold text-slate-400 px-2.5 py-1.5 bg-slate-100 rounded-lg cursor-not-allowed">
                                                                Sedang Diverifikasi
                                                            </span>
                                                        @elseif($item->user_id === auth()->id())
                                                            <span class="inline-flex text-[10px] font-bold text-slate-400 px-2.5 py-1.5 bg-slate-100 rounded-lg">
                                                                Laporan Kamu
                                                            </span>
                                                        @else
                                                            <details class="text-left group">
                                                                <summary class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-emerald-600 hover:text-white bg-emerald-50 hover:bg-emerald-600 border border-emerald-200 rounded-lg transition-all duration-200 cursor-pointer list-none hover:border-emerald-700 hover:shadow-md">
                                                                    + Klaim Barang
                                                                </summary>
                                                                <form action="{{ route('items.claim', $item->id) }}" method="POST" class="mt-3 bg-slate-50 border border-slate-200 rounded-xl p-4 w-64 shadow-lg">
                                                                    @csrf
                                                                    <label class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider block mb-2">Bukti Kepemilikan <span class="text-rose-500">*</span></label>
                                                                    <textarea name="claim_message" rows="4" required placeholder="Contoh: warna, merek, isi tas/dompet, nomor seri, gantungan kunci, atau ciri khusus yang hanya pemilik tahu." class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 resize-none font-medium"></textarea>
                                                                    <div class="flex gap-2 mt-3">
                                                                        <button type="button" onclick="this.parentElement.closest('form').parentElement.open = false" class="flex-1 px-3 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-all cursor-pointer">
                                                                            Batal
                                                                        </button>
                                                                        <button type="submit" class="flex-1 px-3 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-lg transition-all cursor-pointer shadow-sm hover:shadow">
                                                                            Ajukan Klaim
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </details>
                                                        @endif
                                                    @elseif($item->status === 'hilang' && $item->user_id === auth()->id())
                                                        <form action="{{ route('items.update', $item->id) }}" method="POST" onsubmit="return confirm('Tandai barang hilang ini sudah ditemukan kembali?');">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="diklaim">
                                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition-all duration-200 cursor-pointer">
                                                                Sudah Ketemu
                                                            </button>
                                                        </form>
                                                    @elseif($item->status === 'diklaim' && $item->claimant_id === auth()->id())
                                                        <form action="{{ route('items.unclaim', $item->id) }}" method="POST" onsubmit="return confirm('Batalkan klaim kamu?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition-all duration-200 cursor-pointer">
                                                                Batalkan Klaim
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="inline-flex items-center text-[11px] font-bold text-slate-400 px-2 py-1 bg-slate-100 rounded-lg">
                                                            Selesai
                                                        </span>
                                                    @endif
                                                @endif
                                            @else
                                                @if($item->status === 'ditemukan')
                                                    <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-1.5 text-[11px] font-bold text-indigo-600 hover:text-white bg-indigo-50 hover:bg-indigo-600 border border-indigo-200 rounded-lg transition-all duration-200">
                                                        Masuk untuk mengklaim
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center text-[11px] font-bold text-slate-400 px-2 py-1 bg-slate-100 rounded-lg">
                                                        Lihat Detail
                                                    </span>
                                                @endif
                                            @endauth
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <a href="{{ route('items.show', $item->id) }}" class="inline-flex items-center px-3 py-2 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg transition-colors">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="mt-8 flex justify-center">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </main>
    @endif
    @endauth

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200/80 py-6 mt-12 flex-shrink-0 {{ $isAdmin ? 'lg:ml-64' : '' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-400 font-medium">
            <p>&copy; {{ date('Y') }} Lost & Found - Fakultas Teknik Universitas Sultan Ageng Tirtayasa. Semua Hak Dilindungi.</p>
            <p class="mt-1.5 text-indigo-500/80">Dikembangkan untuk Dokumentasi Tugas Akhir Sistem Basis Data Lanjut FT UNTIRTA.</p>
        </div>
    </footer>

    <!-- MODAL FORM LAPOR BARANG BARU -->
    <div id="reportModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay Latar Belakang Gelap -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeModal()" aria-hidden="true"></div>

            <!-- Trik browser untuk memusatkan modal secara vertikal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Konten Modal -->
            <div class="inline-flex align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-200 animate-pop-in max-h-[90vh] flex-col">
                <!-- Header Modal -->
                <div class="bg-indigo-900 px-6 py-4 flex items-center justify-between text-white flex-shrink-0">
                    <h3 class="text-base font-bold tracking-tight" id="modal-title">
                        Buat Laporan Barang Baru
                    </h3>
                    <button onclick="closeModal()" class="text-indigo-200 hover:text-white transition-colors cursor-pointer">
                        <!-- Icon Close (SVG) -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Form Input -->
                <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-4 space-y-4 overflow-y-auto flex-1">
                    @csrf

                    <!-- Nama Barang -->
                    <div>
                        <label for="nama_barang" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Barang <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang') }}" placeholder="Contoh: Kunci Motor Honda Vario" class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('nama_barang') ? 'border-rose-300 focus:ring-rose-200 focus:border-rose-500' : 'border-slate-200 focus:ring-indigo-200 focus:border-indigo-500' }} focus:outline-none focus:ring-2 text-sm font-medium">
                        @error('nama_barang')
                            <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Kategori -->
                        <div>
                            <label for="kategori" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kategori <span class="text-rose-500">*</span></label>
                            <select name="kategori" id="kategori" class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('kategori') ? 'border-rose-300 focus:ring-rose-200 focus:border-rose-500' : 'border-slate-200 focus:ring-indigo-200 focus:border-indigo-500' }} focus:outline-none focus:ring-2 text-sm font-medium bg-white">
                                <option value="">Pilih Kategori</option>
                                <option value="Elektronik" {{ old('kategori') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                <option value="Dokumen & Kartu" {{ old('kategori') == 'Dokumen & Kartu' ? 'selected' : '' }}>Dokumen & Kartu</option>
                                <option value="Kunci" {{ old('kategori') == 'Kunci' ? 'selected' : '' }}>Kunci</option>
                                <option value="Pakaian & Aksesoris" {{ old('kategori') == 'Pakaian & Aksesoris' ? 'selected' : '' }}>Pakaian & Aksesoris</option>
                                <option value="Tas & Dompet" {{ old('kategori') == 'Tas & Dompet' ? 'selected' : '' }}>Tas & Dompet</option>
                                <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lain-lain</option>
                            </select>
                            @error('kategori')
                                <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Laporan -->
                        <div>
                            <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status Laporan <span class="text-rose-500">*</span></label>
                            <select name="status" id="status" onchange="toggleFoundFields()" class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('status') ? 'border-rose-300 focus:ring-rose-200 focus:border-rose-500' : 'border-slate-200 focus:ring-indigo-200 focus:border-indigo-500' }} focus:outline-none focus:ring-2 text-sm font-medium bg-white">
                                <option value="ditemukan" {{ old('status', 'ditemukan') == 'ditemukan' ? 'selected' : '' }}>Ditemukan (Barang Temuan)</option>
                                <option value="hilang" {{ old('status') == 'hilang' ? 'selected' : '' }}>Hilang (Melaporkan Kehilangan)</option>
                            </select>
                            @error('status')
                                <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="found-fields" class="grid grid-cols-1 sm:grid-cols-2 gap-4 {{ old('status', 'ditemukan') === 'hilang' ? 'hidden' : '' }}">
                        <div>
                            <label for="penemu" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Penemu <span class="text-rose-500">*</span></label>
                            <input type="text" name="penemu" id="penemu" value="{{ old('penemu') }}" placeholder="Nama orang yang menemukan" class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('penemu') ? 'border-rose-300 focus:ring-rose-200 focus:border-rose-500' : 'border-slate-200 focus:ring-indigo-200 focus:border-indigo-500' }} focus:outline-none focus:ring-2 text-sm font-medium">
                            @error('penemu')
                                <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="penanganan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Barang Disimpan Oleh <span class="text-rose-500">*</span></label>
                            <select name="penanganan" id="penanganan" class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('penanganan') ? 'border-rose-300 focus:ring-rose-200 focus:border-rose-500' : 'border-slate-200 focus:ring-indigo-200 focus:border-indigo-500' }} focus:outline-none focus:ring-2 text-sm font-medium bg-white">
                                <option value="">Pilih kondisi barang</option>
                                <option value="dibawa_penemu" {{ old('penanganan') === 'dibawa_penemu' ? 'selected' : '' }}>Dibawa oleh penemu</option>
                                <option value="diserahkan_satpam" {{ old('penanganan') === 'diserahkan_satpam' ? 'selected' : '' }}>Dititipkan ke satpam</option>
                            </select>
                            @error('penanganan')
                                <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Lokasi -->
                        <div>
                            <label for="lokasi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Lokasi Kejadian <span class="text-rose-500">*</span></label>
                            <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Masjid Lantai 1 / Kantin FT" class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('lokasi') ? 'border-rose-300 focus:ring-rose-200 focus:border-rose-500' : 'border-slate-200 focus:ring-indigo-200 focus:border-indigo-500' }} focus:outline-none focus:ring-2 text-sm font-medium">
                            @error('lokasi')
                                <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Kejadian -->
                        <div>
                            <label for="tanggal_temu" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tanggal Kejadian <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_temu" id="tanggal_temu" max="{{ date('Y-m-d') }}" value="{{ old('tanggal_temu', date('Y-m-d')) }}" class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('tanggal_temu') ? 'border-rose-300 focus:ring-rose-200 focus:border-rose-500' : 'border-slate-200 focus:ring-indigo-200 focus:border-indigo-500' }} focus:outline-none focus:ring-2 text-sm font-medium">
                            @error('tanggal_temu')
                                <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Upload Foto -->
                    <div>
                        <label for="foto" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Foto Barang (Opsional)</label>
                        <div class="flex items-center justify-center w-full">
                        <label for="foto" class="flex flex-col items-center justify-center w-full min-h-[140px] border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors duration-200 p-4">
                            <div id="foto-placeholder" class="flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-8 h-8 mb-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"></path>
                                </svg>
                                <p id="foto-text" class="text-xs font-semibold">Klik untuk unggah atau seret berkas foto</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">PNG, JPG, JPEG, atau WEBP (Maksimal 2MB)</p>
                            </div>
                            <img id="foto-preview" src="" alt="Preview" class="hidden max-h-32 max-w-full object-contain rounded-xl shadow-sm mt-3">
                            <input type="file" name="foto" id="foto" accept="image/*" class="hidden">
                        </label>
                    </div>
                        @error('foto')
                            <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi & Ciri-Ciri Barang <span class="text-rose-500">*</span></label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" placeholder="Tuliskan deskripsi lengkap, warna, merk, no seri, atau ciri khas barang agar mudah dikenali..." class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('deskripsi') ? 'border-rose-300 focus:ring-rose-200 focus:border-rose-500' : 'border-slate-200 focus:ring-indigo-200 focus:border-indigo-500' }} focus:outline-none focus:ring-2 text-sm font-medium">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Footer Form / Tombol Submit -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 bg-slate-50 flex-shrink-0 px-6 py-4">
                        <button type="button" onclick="closeModal()" class="px-4 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-800 rounded-xl hover:bg-slate-100 transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all duration-200 shadow-sm hover:shadow cursor-pointer">
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PROFILE EDIT MODAL -->
    @auth
    <div id="profileModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="profile-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeProfileModal()" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-flex align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-200 animate-pop-in max-h-[90vh] flex-col">
                <div class="bg-indigo-900 px-6 py-4 flex items-center justify-between text-white flex-shrink-0">
                    <div>
                        <h3 class="text-base font-bold tracking-tight" id="profile-modal-title">Edit Profil</h3>
                        <p class="text-sm text-indigo-100">Perbarui nama, email, atau kata sandi Anda.</p>
                    </div>
                    <button type="button" onclick="closeProfileModal()" class="text-indigo-200 hover:text-white transition-colors cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('profile.update') }}" method="POST" class="px-6 py-4 space-y-5 overflow-y-auto flex-1">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="profile_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="profile_name" value="{{ old('name', auth()->user()->name) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-200 focus:outline-none">
                        @error('name')
                            <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="profile_email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" id="profile_email" value="{{ old('email', auth()->user()->email) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-200 focus:outline-none">
                        @error('email')
                            <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    @if(auth()->user()->role === 'mahasiswa')
                        <div>
                            <label for="profile_nim" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">NIM</label>
                            <input type="text" name="nim" id="profile_nim" value="{{ old('nim', auth()->user()->nim) }}" inputmode="numeric" minlength="8" maxlength="15" pattern="[0-9]{8,15}" required class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-200 focus:outline-none">
                            @error('nim')
                                <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label for="profile_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Kata Sandi Baru</label>
                            <input type="password" name="password" id="profile_password" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-200 focus:outline-none" placeholder="Kosongkan jika tidak diubah">
                            @error('password')
                                <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="profile_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" id="profile_password_confirmation" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-200 focus:outline-none" placeholder="Ulangi kata sandi baru">
                            @error('password_confirmation')
                                <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="inline-flex items-center justify-center px-5 py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-2xl shadow-sm transition-all">Simpan Profil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS PENGGUNA ANGGOTA -->
    <div id="deleteUserModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="delete-user-modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteUserModal()" aria-hidden="true"></div>
            <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left shadow-2xl transition-all border border-slate-200 animate-pop-in">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-bold text-slate-900" id="delete-user-modal-title">Hapus Akun Mahasiswa</h3>
                        <p class="mt-2 text-xs text-slate-600 leading-relaxed">
                            Yakin ingin menghapus akun <span id="deleteUserName" class="font-bold text-slate-900"></span> (<span id="deleteUserNim" class="font-bold text-slate-900"></span>)?
                        </p>
                        <p class="mt-2 text-xs text-rose-700 leading-relaxed bg-rose-50 p-2.5 rounded-xl border border-rose-100 font-medium">
                            Semua data laporan terkait akun ini akan dihapus. NIM dan email dapat langsung didaftarkan kembali.
                        </p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2.5">
                    <button type="button" onclick="closeDeleteUserModal()" class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <form id="deleteUserForm" action="" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-rose-700 transition shadow-sm hover:shadow cursor-pointer">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endauth

    <!-- NOTIFIKASI TOAST SUKSES -->
    @if(session('success'))
        <div id="toast" class="fixed bottom-5 right-5 z-50 flex items-center p-4 mb-4 text-slate-800 bg-white rounded-2xl border border-slate-150 shadow-2xl transition-all duration-500 transform translate-y-0" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-9 h-9 text-emerald-500 bg-emerald-50 rounded-xl shadow-sm mr-3">
                <!-- Icon Checklist Success (SVG) -->
                <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-bold text-slate-900">Operasi Sukses!</span>
                <span class="text-xs text-slate-500 font-medium mt-0.5">{{ session('success') }}</span>
            </div>
            <button onclick="dismissToast()" class="ml-auto -mx-1.5 -my-1.5 bg-white text-slate-400 hover:text-slate-900 rounded-lg p-1.5 hover:bg-slate-100 inline-flex h-8 w-8 transition-colors duration-200 cursor-pointer">
                <!-- Icon Close small -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div id="errorToast" class="fixed bottom-5 left-5 z-50 flex items-start p-4 mb-4 text-slate-800 bg-white rounded-2xl border border-rose-150 shadow-2xl transition-all duration-500 max-w-md" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-9 h-9 text-rose-500 bg-rose-50 rounded-xl shadow-sm mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM10.29 3.86L1.82 18a1.5 1.5 0 001.29 2.25h17.78A1.5 1.5 0 0022.18 18L13.71 3.86a1.5 1.5 0 00-2.42 0z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-bold text-slate-900">Ada data yang perlu diperbaiki</span>
                <span class="text-xs text-slate-500 font-medium mt-0.5">{{ $errors->first() }}</span>
            </div>
        </div>
    @endif

    <!-- LOGIKA JAVASCRIPT UNTUK MODAL & TOAST -->
    <script>
        function toggleFoundFields() {
            const foundFields = document.getElementById('found-fields');
            const isFound = document.getElementById('status').value === 'ditemukan';
            foundFields.classList.toggle('hidden', !isFound);
            foundFields.querySelectorAll('input, select').forEach((field) => {
                field.required = isFound;
                field.disabled = !isFound;
            });
        }

        toggleFoundFields();

        // Membuka modal form
        function openModal() {
            const modal = document.getElementById('reportModal');
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        // Menutup modal form
        function closeModal() {
            const modal = document.getElementById('reportModal');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Membuka modal profil
        function openProfileModal() {
            const modal = document.getElementById('profileModal');
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        // Menutup modal profil
        function closeProfileModal() {
            const modal = document.getElementById('profileModal');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Membuka modal konfirmasi hapus pengguna anggota
        function openDeleteUserModal(actionUrl, userName, userNim) {
            const modal = document.getElementById('deleteUserModal');
            const form = document.getElementById('deleteUserForm');
            const nameEl = document.getElementById('deleteUserName');
            const nimEl = document.getElementById('deleteUserNim');
            if (modal && form && nameEl && nimEl) {
                form.action = actionUrl;
                nameEl.textContent = userName;
                nimEl.textContent = 'NIM: ' + userNim;
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        // Menutup modal konfirmasi hapus pengguna anggota
        function closeDeleteUserModal() {
            const modal = document.getElementById('deleteUserModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Menyembunyikan toast notifikasi secara manual
        function dismissToast() {
            const toast = document.getElementById('toast');
            if (toast) {
                toast.classList.add('opacity-0', 'translate-y-5');
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }
        }

        // Menghilangkan toast secara otomatis setelah 5 detik
        @if(session('success'))
            setTimeout(() => {
                dismissToast();
            }, 5000);
        @endif

        // Otomatis membuka modal form laporan barang jika validation error terjadi pada form laporan
        @if($errors->has('nama_barang') || $errors->has('kategori') || $errors->has('lokasi') || $errors->has('tanggal_temu') || $errors->has('deskripsi') || $errors->has('status') || $errors->has('penemu') || $errors->has('penanganan') || $errors->has('foto'))
            window.addEventListener('DOMContentLoaded', (event) => {
                openModal();
            });
        @endif

        // Otomatis membuka modal profil jika validation error terjadi pada profile
        @if($errors->has('name') || $errors->has('email') || $errors->has('nim') || $errors->has('password') || $errors->has('password_confirmation'))
            window.addEventListener('DOMContentLoaded', (event) => {
                openProfileModal();
            });
        @endif

        const fotoInput = document.getElementById('foto');
        const fotoText = document.getElementById('foto-text');
        const fotoPlaceholder = document.getElementById('foto-placeholder');
        const fotoPreview = document.getElementById('foto-preview');
        if (fotoInput && fotoText) {
            fotoInput.addEventListener('change', function () {
                if (this.files.length > 0) {
                    const file = this.files[0];
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        fotoPreview.src = e.target.result;
                        fotoPreview.classList.remove('hidden');
                        fotoPlaceholder.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    fotoPreview.classList.add('hidden');
                    fotoPlaceholder.classList.remove('hidden');
                    fotoText.textContent = 'Klik untuk unggah atau seret berkas foto';
                }
            });
        }

        function cardNavigate(event, element, url) {
            const target = event.target;
            if (target.closest('button, a, form, input, textarea, select, summary, details')) {
                return;
            }
            window.location = url;
        }
    </script>

</body>
</html>
