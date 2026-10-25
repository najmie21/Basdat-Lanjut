@props(['adminTab' => 'dashboard'])

<header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/80 shadow-sm backdrop-blur-md">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center space-x-2 sm:space-x-3" aria-label="Lost & Found FT UNTIRTA - Dashboard">
                <x-logo class="h-10 w-10 shrink-0 sm:h-11 sm:w-11" />
                <span>
                    <span class="block text-lg font-bold leading-none tracking-tight text-slate-900">Lost & Found FT</span>
                    <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600">UNTIRTA</span>
                </span>
            </a>

            <div class="flex items-center gap-2 sm:gap-4">
                @if($adminTab === 'items')
                    <button onclick="openModal()" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-3 py-2.5 text-xs font-semibold text-white shadow-md shadow-indigo-100 transition-all-300 hover:bg-indigo-700 hover:shadow-lg sm:px-4 sm:text-sm">
                        <svg class="mr-1 h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                        <span class="hidden sm:inline">Laporkan Barang</span>
                        <span class="sm:hidden">Lapor</span>
                    </button>
                @endif

                <div class="flex items-center gap-2 border-l border-slate-200 pl-2 sm:gap-3 sm:pl-4">
                    @if($adminTab === 'dashboard')
                        <span class="hidden text-xs font-bold text-slate-800 sm:inline">{{ auth()->user()->name }}</span>
                    @else
                        <button type="button" onclick="openProfileModal()" title="Edit Profile" class="hidden text-xs font-bold text-slate-800 hover:text-indigo-600 sm:inline">{{ auth()->user()->name }}</button>
                    @endif
                    <span class="rounded-md border border-indigo-200 bg-indigo-100 px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider text-indigo-700">Admin</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" title="Keluar" class="inline-flex items-center justify-center rounded-xl p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<aside class="fixed bottom-0 left-0 top-16 z-30 hidden w-64 flex-col border-r border-slate-200 bg-white px-4 py-6 lg:flex">
    <nav class="space-y-1" aria-label="Navigasi admin">
        <a href="{{ route('admin.dashboard') }}" @class(['flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition-colors', 'bg-indigo-50 text-indigo-700' => $adminTab === 'dashboard', 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => $adminTab !== 'dashboard'])>
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h6.5v6.5h-6.5zm10 0h6.5v6.5h-6.5zm-10 10h6.5v6.5h-6.5zm10 0h6.5v6.5h-6.5z"></path></svg>
            Dashboard & Statistik
        </a>
        <a href="{{ route('items.index', ['admin_tab' => 'users']) }}" @class(['flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition-colors', 'bg-indigo-50 text-indigo-700' => $adminTab === 'users', 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => $adminTab !== 'users'])>
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.1a8.9 8.9 0 004.5 1.15v-1.5a3.75 3.75 0 00-6.67-2.35M15 19.1v1.15H4.5v-1.5a3.75 3.75 0 016.67-2.35M15 19.1a6 6 0 00-3.83-2.7M11.17 16.4a3.75 3.75 0 10-6.34 0m10.42-8.15a3 3 0 11-6 0 3 3 0 016 0zm-6.75 1.5a2.75 2.75 0 11-5.5 0 2.75 2.75 0 015.5 0z"></path></svg>
            Daftar Pengguna
        </a>
        <a href="{{ route('admin.admins.index') }}" @class(['flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition-colors', 'bg-indigo-50 text-indigo-700' => $adminTab === 'admins', 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => $adminTab !== 'admins'])>
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m14-13a4 4 0 11-8 0 4 4 0 018 0zm3 13v-2a4 4 0 00-3-3.87"></path></svg>
            Kelola Admin
        </a>
        <a href="{{ route('items.index', ['admin_tab' => 'items']) }}" @class(['flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition-colors', 'bg-indigo-50 text-indigo-700' => $adminTab === 'items', 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => $adminTab !== 'items'])>
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5h15m-13.5 0v11.25A1.75 1.75 0 007.75 20.5h8.5A1.75 1.75 0 0018 18.75V7.5M9 7.5V5.25A1.75 1.75 0 0110.75 3.5h2.5A1.75 1.75 0 0115 5.25V7.5m-4.5 4h3"></path></svg>
            Daftar Barang
        </a>
    </nav>
    <div class="mt-auto rounded-xl bg-slate-50 p-3">
        <p class="text-xs font-bold text-slate-700">Lost & Found FT</p>
        <p class="mt-1 text-[11px] leading-relaxed text-slate-500">Universitas Sultan Ageng Tirtayasa</p>
    </div>
</aside>

<nav class="flex gap-2 overflow-x-auto border-b border-slate-200 bg-white px-4 py-2 lg:hidden" aria-label="Navigasi admin">
    <a href="{{ route('admin.dashboard') }}" class="whitespace-nowrap rounded-lg px-3 py-2 text-xs font-bold {{ $adminTab === 'dashboard' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600' }}">Dashboard</a>
    <a href="{{ route('items.index', ['admin_tab' => 'users']) }}" class="whitespace-nowrap rounded-lg px-3 py-2 text-xs font-bold {{ $adminTab === 'users' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600' }}">Pengguna</a>
    <a href="{{ route('admin.admins.index') }}" class="whitespace-nowrap rounded-lg px-3 py-2 text-xs font-bold {{ $adminTab === 'admins' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600' }}">Kelola Admin</a>
    <a href="{{ route('items.index', ['admin_tab' => 'items']) }}" class="whitespace-nowrap rounded-lg px-3 py-2 text-xs font-bold {{ $adminTab === 'items' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600' }}">Barang</a>
</nav>
