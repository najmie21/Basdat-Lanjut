<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password Baru - Lost & Found FT UNTIRTA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-dvh bg-slate-50 px-4 py-10 text-slate-900 bg-grid-pattern">
    <main class="mx-auto max-w-md pt-10">
        <div class="mb-6 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-200">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Langkah keamanan</p>
            <h1 class="mt-2 text-2xl font-extrabold">Buat password baru</h1>
            <p class="mt-2 text-sm leading-relaxed text-slate-600">Password sementara sudah terpakai dan tidak dapat digunakan lagi. Atur password pribadi untuk melanjutkan.</p>
        </div>

        <form action="{{ route('password.change.update') }}" method="POST" class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-xl">
            @csrf
            <div>
                <label for="password" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700">Password baru</label>
                <div class="relative">
                    <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required class="w-full rounded-xl border border-slate-200 pl-4 pr-12 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none">
                    <button type="button" data-password-toggle="password" aria-label="Tampilkan kata sandi" aria-pressed="false" class="absolute inset-y-0 right-0 z-20 flex items-center px-3.5 text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-none" title="Tampilkan kata sandi">
                        <svg data-eye-show class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.5-6.25 9.75-6.25S21.75 12 21.75 12s-3.5 6.25-9.75 6.25S2.25 12 2.25 12z"></path><circle cx="12" cy="12" r="2.75"></circle></svg>
                        <svg data-eye-hide class="hidden h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 5.9A10.7 10.7 0 0112 5.75c6.25 0 9.75 6.25 9.75 6.25a17 17 0 01-3.1 3.8M6.2 6.2C3.65 7.75 2.25 12 2.25 12s3.5 6.25 9.75 6.25c1.5 0 2.8-.35 3.9-.9"></path></svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700">Konfirmasi password baru</label>
                <div class="relative">
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required class="w-full rounded-xl border border-slate-200 pl-4 pr-12 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none">
                    <button type="button" data-password-toggle="password_confirmation" aria-label="Tampilkan konfirmasi kata sandi" aria-pressed="false" class="absolute inset-y-0 right-0 z-20 flex items-center px-3.5 text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-none" title="Tampilkan konfirmasi kata sandi">
                        <svg data-eye-show class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.5-6.25 9.75-6.25S21.75 12 21.75 12s-3.5 6.25-9.75 6.25S2.25 12 2.25 12z"></path><circle cx="12" cy="12" r="2.75"></circle></svg>
                        <svg data-eye-hide class="hidden h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 5.9A10.7 10.7 0 0112 5.75c6.25 0 9.75 6.25 9.75 6.25a17 17 0 01-3.1 3.8M6.2 6.2C3.65 7.75 2.25 12 2.25 12s3.5 6.25 9.75 6.25c1.5 0 2.8-.35 3.9-.9"></path></svg>
                    </button>
                </div>
            </div>
            <button type="submit" class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white transition-colors hover:bg-indigo-700">Simpan password baru</button>
            <button type="submit" form="logout-form" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-50">Keluar</button>
        </form>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </main>
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