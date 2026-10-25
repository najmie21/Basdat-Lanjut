<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Lost & Found FT UNTIRTA</title>
    
    <!-- Import Google Fonts (Plus Jakarta Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Memuat aset CSS & JS menggunakan Vite compiler bawaan Laravel -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .transition-all-300 {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        /* Sembunyikan ikon mata bawaan browser (Edge/IE) agar tidak menimpa tombol intip */
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-dvh flex items-start sm:items-center justify-center px-4 py-8 relative overflow-x-hidden overflow-y-auto bg-grid-pattern">
    
    <!-- Dekorasi Latar Belakang -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-indigo-300 via-slate-100 to-slate-200 pointer-events-none"></div>
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-500 rounded-full blur-3xl opacity-20 pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-pink-500 rounded-full blur-3xl opacity-20 pointer-events-none"></div>

    <div class="max-w-md w-full relative z-10 my-auto">
        <!-- Logo & Judul Aplikasi -->
        <div class="text-center mb-5 sm:mb-8">
            <x-logo class="mx-auto mb-3 h-16 w-16 transition-transform duration-300 hover:scale-105" />
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Lost & Found FT</h1>
            <p class="text-sm font-bold text-indigo-600 tracking-wider uppercase mt-1">Universitas Sultan Ageng Tirtayasa</p>
        </div>

        <!-- Card Register -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xl p-6 sm:p-8 transition-all duration-300">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-900">Buat Akun Baru</h2>
                <p class="text-xs text-slate-500 mt-1">Daftarkan diri Anda untuk mulai melaporkan dan mengklaim barang di lingkungan kampus.</p>
            </div>

            <!-- Form Register -->
            <form action="{{ route('register') }}" method="POST" class="space-y-4" id="registerForm">
                @csrf

                <!-- Input Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <!-- Icon User (SVG) -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                            </svg>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Nama Lengkap Anda" class="w-full pl-10 pr-4 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-rose-300 focus:ring-rose-200 focus:border-rose-500' : 'border-slate-200 focus:ring-indigo-200 focus:border-indigo-500' }} focus:outline-none focus:ring-2 text-sm font-medium text-slate-800 placeholder-slate-400" required>
                    </div>
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <!-- Icon Envelope (SVG) -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path>
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="nama@gmail.com" class="w-full pl-10 pr-4 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-rose-300 focus:ring-rose-200 focus:border-rose-500' : 'border-slate-200 focus:ring-indigo-200 focus:border-indigo-500' }} focus:outline-none focus:ring-2 text-sm font-medium text-slate-800 placeholder-slate-400" required>
                    </div>
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div id="nim-field">
                    <label for="nim" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">NIM</label>
                    <input type="text" name="nim" id="nim" value="{{ old('nim') }}" inputmode="numeric" autocomplete="off" minlength="8" maxlength="15" pattern="[0-9]{8,15}" placeholder="8 sampai 15 digit" required class="w-full rounded-xl border {{ $errors->has('nim') ? 'border-rose-300 focus:ring-rose-200 focus:border-rose-500' : 'border-slate-200 focus:ring-indigo-200 focus:border-indigo-500' }} px-4 py-2.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2">
                    @error('nim')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <!-- Icon Key (SVG) -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"></path>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" class="w-full pl-10 pr-12 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-300 focus:ring-rose-200 focus:border-rose-500' : 'border-slate-200 focus:ring-indigo-200 focus:border-indigo-500' }} focus:outline-none focus:ring-2 text-sm font-medium text-slate-800 placeholder-slate-400" required>
                        <button type="button" data-password-toggle="password" aria-label="Tampilkan kata sandi" aria-pressed="false" class="absolute inset-y-0 right-0 z-20 flex items-center px-3.5 text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-none" title="Tampilkan kata sandi">
                            <svg data-eye-show class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.5-6.25 9.75-6.25S21.75 12 21.75 12s-3.5 6.25-9.75 6.25S2.25 12 2.25 12z"></path><circle cx="12" cy="12" r="2.75"></circle></svg>
                            <svg data-eye-hide class="hidden h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 5.9A10.7 10.7 0 0112 5.75c6.25 0 9.75 6.25 9.75 6.25a17 17 0 01-3.1 3.8M6.2 6.2C3.65 7.75 2.25 12 2.25 12s3.5 6.25 9.75 6.25c1.5 0 2.8-.35 3.9-.9"></path></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <!-- Icon Key Check (SVG) -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.285z"></path>
                            </svg>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi kata sandi" class="w-full pl-10 pr-12 py-2.5 rounded-xl border border-slate-200 focus:ring-indigo-200 focus:border-indigo-500 focus:outline-none focus:ring-2 text-sm font-medium text-slate-800 placeholder-slate-400" required>
                        <button type="button" data-password-toggle="password_confirmation" aria-label="Tampilkan konfirmasi kata sandi" aria-pressed="false" class="absolute inset-y-0 right-0 z-20 flex items-center px-3.5 text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-none" title="Tampilkan konfirmasi kata sandi">
                            <svg data-eye-show class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.5-6.25 9.75-6.25S21.75 12 21.75 12s-3.5 6.25-9.75 6.25S2.25 12 2.25 12z"></path><circle cx="12" cy="12" r="2.75"></circle></svg>
                            <svg data-eye-hide class="hidden h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 5.9A10.7 10.7 0 0112 5.75c6.25 0 9.75 6.25 9.75 6.25s-3.5 6.25-9.75 6.25c-1.5 0-2.8-.35-3.9-.9"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-xl transition-all-300 shadow-md shadow-indigo-100 hover:shadow-lg focus:outline-none cursor-pointer">
                    Daftar Sekarang
                </button>
            </form>

            <!-- Tautan ke halaman masuk -->
            <div class="mt-6 text-center border-t border-slate-100 pt-6">
                <p class="text-xs text-slate-500 font-semibold">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-bold transition-colors">Masuk di sini</a>
                </p>
            </div>

            <!-- Tombol Kembali Ke Beranda -->
            <div class="mt-4 text-center">
                <a href="{{ route('items.index') }}" class="inline-flex items-center text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors">
                    <!-- Icon Back arrow -->
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            const input = document.getElementById(button.dataset.passwordToggle);
            const showIcon = button.querySelector('[data-eye-show]');
            const hideIcon = button.querySelector('[data-eye-hide]');

            button.addEventListener('click', (e) => {
                e.preventDefault();
                const showPassword = input.type === 'password';
                input.type = showPassword ? 'text' : 'password';
                button.setAttribute('aria-pressed', String(showPassword));
                button.setAttribute('aria-label', showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
                button.title = showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi';
                showIcon.classList.toggle('hidden', showPassword);
                hideIcon.classList.toggle('hidden', !showPassword);
                input.focus();
            });
        });
    </script>
</body>
</html>
