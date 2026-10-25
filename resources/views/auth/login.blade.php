<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Lost & Found FT UNTIRTA</title>
    
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
        /* Sembunyikan ikon reveal bawaan browser (Edge/IE) */
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
        <!-- Kartu masuk -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xl p-6 sm:p-8 transition-all duration-300">
            @if(session('password_reset_status') && !session('password_reset_error'))
                <div class="mb-5 rounded-xl border border-indigo-100 bg-indigo-50 p-3.5 text-xs leading-relaxed text-indigo-800 flex items-start gap-2.5" role="status">
                    <svg class="w-4 h-4 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-indigo-900">Tautan Verifikasi Terkirim</p>
                        <p class="text-xs text-indigo-700 mt-0.5">{{ session('password_reset_status') }}</p>
                    </div>
                </div>
            @endif

            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-900">Selamat Datang</h2>
                <p class="text-xs text-slate-500 mt-1">Silakan masuk untuk mengakses fitur pelaporan dan klaim barang.</p>
            </div>

            <!-- Formulir masuk -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

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
                        <input type="email" name="email" id="email" value="{{ old('email') }}" autocomplete="off" placeholder="nama@gmail.com" class="w-full pl-10 pr-4 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-rose-300 focus:ring-rose-200 focus:border-rose-500' : 'border-slate-200 focus:ring-indigo-200 focus:border-indigo-500' }} focus:outline-none focus:ring-2 text-sm font-medium text-slate-800 placeholder-slate-400" required>
                    </div>
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <!-- Icon Key (SVG) -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"></path>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="w-full pl-10 pr-12 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-300 focus:ring-rose-200 focus:border-rose-500' : 'border-slate-200 focus:ring-indigo-200 focus:border-indigo-500' }} focus:outline-none focus:ring-2 text-sm font-medium text-slate-800 placeholder-slate-400" required>
                        <button type="button" data-password-toggle="password" aria-label="Tampilkan kata sandi" aria-pressed="false" class="absolute inset-y-0 right-0 z-20 flex items-center px-3.5 text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-none" title="Tampilkan kata sandi">
                            <svg data-eye-show class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.5-6.25 9.75-6.25S21.75 12 21.75 12s-3.5 6.25-9.75 6.25S2.25 12 2.25 12z"></path><circle cx="12" cy="12" r="2.75"></circle></svg>
                            <svg data-eye-hide class="hidden h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 5.9A10.7 10.7 0 0112 5.75c6.25 0 9.75 6.25 9.75 6.25a17 17 0 01-3.1 3.8M6.2 6.2C3.65 7.75 2.25 12 2.25 12s3.5 6.25 9.75 6.25c1.5 0 2.8-.35 3.9-.9"></path></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                    <button type="button" onclick="openForgotPasswordModal()" class="mt-2 text-xs font-bold text-indigo-600 hover:text-indigo-800">Lupa kata sandi?</button>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                    <label for="remember" class="ml-2 text-xs font-semibold text-slate-500 select-none">Ingat saya di perangkat ini</label>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-xl transition-all-300 shadow-md shadow-indigo-100 hover:shadow-lg focus:outline-none cursor-pointer">
                    Masuk Sekarang
                </button>
            </form>

            <!-- Tautan Registrasi -->
            <p class="text-xs text-center text-slate-500 mt-4">
                Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700 font-bold transition-colors">Daftar Sekarang</a>
            </p>

            <!-- Tombol Kembali Ke Beranda -->
            <div class="mt-6 text-center">
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
    <div id="forgotPasswordModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="forgot-password-title">
        <div class="flex min-h-full items-center justify-center p-4">
            <button type="button" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" aria-label="Tutup popup" onclick="closeForgotPasswordModal()"></button>
            <section class="relative z-10 w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
                <button type="button" onclick="closeForgotPasswordModal()" class="absolute right-4 top-4 rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup popup">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Pemulihan akun</p>
                <h2 id="forgot-password-title" class="mt-2 text-xl font-extrabold text-slate-900">Lupa kata sandi?</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">Masukkan email akun dan NIM. Jika keduanya cocok, tautan verifikasi akan dikirim ke inbox email terdaftar.</p>

                @if(session('password_reset_error'))
                    <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-3.5 text-sm leading-relaxed text-rose-800 flex items-start gap-2.5" role="alert">
                        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path>
                        </svg>
                        <div>
                            <p class="font-bold text-rose-900">Verifikasi Tidak Sinkron</p>
                            <p class="text-xs text-rose-700 mt-0.5">{{ session('password_reset_error') }}</p>
                        </div>
                    </div>
                @elseif(session('password_reset_status'))
                    <div class="mt-4 rounded-xl border border-indigo-100 bg-indigo-50 p-3.5 text-sm leading-relaxed text-indigo-800 flex items-start gap-2.5" role="status">
                        <svg class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-bold text-indigo-900">Pemberitahuan</p>
                            <p class="text-xs text-indigo-700 mt-0.5">{{ session('password_reset_status') }}</p>
                            <p class="text-[11px] text-indigo-500 font-medium mt-1.5 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                Popup ini akan tertutup otomatis dalam 3 detik...
                            </p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label for="forgot-email" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700">Email akun</label>
                        <input id="forgot-email" name="email" type="email" value="{{ old('email') }}" autocomplete="off" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none" placeholder="nama@gmail.com">
                        @error('email', 'forgotPassword')
                            <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="forgot-nim" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700">NIM</label>
                        <input id="forgot-nim" name="nim" type="text" value="{{ old('nim') }}" inputmode="numeric" autocomplete="off" minlength="8" maxlength="15" pattern="[0-9]{8,15}" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none" placeholder="Masukkan NIM terdaftar">
                        @error('nim', 'forgotPassword')
                            <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white transition-colors hover:bg-indigo-700">Kirim email verifikasi</button>
                </form>
                <p class="mt-4 text-xs leading-relaxed text-slate-500">Tautan hanya dikirim ke email akun yang cocok dengan NIM dan berlaku selama 60 menit.</p>
            </section>
        </div>
    </div>

    <script>
        let autoCloseTimer = null;

        function openForgotPasswordModal() {
            document.getElementById('forgotPasswordModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            document.getElementById('forgot-email').focus();
        }

        function closeForgotPasswordModal() {
            if (autoCloseTimer) {
                clearTimeout(autoCloseTimer);
                autoCloseTimer = null;
            }
            document.getElementById('forgotPasswordModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            if (window.history && window.history.replaceState) {
                const url = new URL(window.location.href);
                if (url.searchParams.has('show_forgot_password')) {
                    url.searchParams.delete('show_forgot_password');
                    const cleanUrl = url.pathname + (url.searchParams.toString() ? '?' + url.searchParams.toString() : '');
                    window.history.replaceState({}, document.title, cleanUrl);
                }
            }
        }

        @if(request()->boolean('show_forgot_password') || session('password_reset_status') || session('password_reset_error') || $errors->getBag('forgotPassword')->any())
            openForgotPasswordModal();
        @endif

        @if(session('password_reset_status') && !session('password_reset_error'))
            autoCloseTimer = setTimeout(() => {
                closeForgotPasswordModal();
            }, 3000);
        @endif

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
