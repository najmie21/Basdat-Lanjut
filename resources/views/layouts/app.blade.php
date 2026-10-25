<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Platform Lost & Found FT UNTIRTA untuk melaporkan dan mencari barang hilang atau ditemukan di lingkungan Fakultas Teknik.">
    <title>@yield('title', 'Lost & Found FT UNTIRTA')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-hidden bg-slate-50 bg-grid-pattern font-sans text-slate-800 antialiased">
    <div class="flex min-h-screen flex-col">
        <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 shadow-sm backdrop-blur-md">
            <div class="mx-auto flex min-h-16 max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
                <a href="{{ route('items.index') }}" class="flex min-w-0 items-center gap-3" aria-label="Lost & Found FT UNTIRTA - Beranda">
                    <x-logo class="h-10 w-10 shrink-0 sm:h-11 sm:w-11" />
                    <span>
                        <span class="block text-base font-extrabold leading-tight tracking-tight text-slate-900 sm:text-lg">Lost &amp; Found FT</span>
                        <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600">UNTIRTA</span>
                    </span>
                </a>

                <nav class="flex items-center gap-2 sm:gap-4" aria-label="Navigasi utama">
                    <a href="{{ route('items.index') }}" class="hidden text-sm font-semibold text-slate-600 transition-colors hover:text-indigo-700 sm:inline">Daftar Barang</a>
                    @auth
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900 sm:px-4 sm:text-sm">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2.5 text-xs font-semibold text-indigo-700 transition-colors hover:bg-indigo-100 sm:px-4 sm:text-sm">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center rounded-xl bg-indigo-600 px-3 py-2.5 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700 sm:px-4 sm:text-sm">
                            Daftar
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="flex-grow">
            @yield('content')
        </main>

        <footer class="mt-auto border-t border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-center text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:text-left lg:px-8">
                <p class="font-semibold text-slate-700">Lost &amp; Found FT UNTIRTA</p>
                <p>&copy; {{ date('Y') }} &middot; Platform pelaporan barang hilang dan barang ditemukan di Fakultas Teknik UNTIRTA.</p>
            </div>
        </footer>
    </div>
</body>
</html>
