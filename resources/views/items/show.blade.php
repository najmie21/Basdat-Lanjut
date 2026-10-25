<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Barang - {{ $item->nama_barang }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
        <header class="mb-6 flex items-center justify-between gap-2 sm:gap-4">
            <a href="{{ route('items.index') }}" class="flex min-w-0 items-center gap-2.5" aria-label="Lost & Found FT UNTIRTA - Beranda">
                <x-logo class="h-10 w-10 shrink-0" />
                <span class="min-w-0">
                    <span class="block text-sm font-extrabold leading-tight text-slate-900">Lost & Found FT</span>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-indigo-600">UNTIRTA</span>
                </span>
            </a>
            <a href="{{ route('items.index') }}" class="inline-flex shrink-0 items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar
            </a>
        </header>

        @if(session('success'))
            <div class="rounded-3xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-700 shadow-sm mb-6">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="rounded-3xl border border-rose-100 bg-rose-50 p-4 text-sm text-rose-700 shadow-sm mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="space-y-6">
            <!-- DETAIL BARANG CARD -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                @if($item->foto_url)
                    <img src="{{ $item->foto_url }}" alt="{{ $item->nama_barang }}" class="w-full h-96 object-cover">
                @else
                    <div class="w-full h-96 bg-slate-100 flex items-center justify-center text-slate-400">
                        <span class="text-sm font-semibold">Tidak ada foto tersedia</span>
                    </div>
                @endif
                
                <div class="p-6 text-center flex flex-col items-center">
                    <div class="mb-4">
                        @if($item->status === 'hilang')
                            <span class="inline-flex items-center px-3.5 py-1.5 text-xs font-bold bg-rose-500 text-white rounded-full uppercase tracking-wider">Hilang</span>
                        @elseif($item->status === 'ditemukan')
                            <span class="inline-flex items-center px-3.5 py-1.5 text-xs font-bold bg-amber-500 text-white rounded-full uppercase tracking-wider">Ditemukan</span>
                        @else
                            <span class="inline-flex items-center px-3.5 py-1.5 text-xs font-bold bg-emerald-500 text-white rounded-full uppercase tracking-wider">Diklaim</span>
                        @endif
                    </div>
                    
                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-3 leading-tight text-center">{{ $item->nama_barang }}</h1>
                    
                    <div class="flex flex-wrap gap-2 items-center justify-center text-xs uppercase tracking-wider mb-6">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 font-semibold shadow-sm">{{ $item->kategori }}</span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200 font-semibold shadow-sm">{{ $item->lokasi }}</span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 font-semibold shadow-sm">{{ $item->tanggal_temu->format('d M Y') }}</span>
                    </div>

                    <div class="w-full bg-slate-50 border border-slate-200 rounded-3xl p-5 text-sm text-slate-700 text-left">
                        <h2 class="text-xs font-bold text-slate-450 uppercase tracking-widest mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Deskripsi & Ciri-ciri
                        </h2>
                        <p class="leading-relaxed whitespace-pre-line font-medium text-slate-650">{{ $item->deskripsi }}</p>
                    </div>
                </div>
            </div>

            <!-- RINGKASAN & TINDAKAN CARD -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Ringkasan Barang</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 text-sm text-slate-700">
                        <div class="rounded-2xl bg-slate-50 p-4 border border-slate-200">
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Pelapor</p>
                            <p class="font-semibold text-slate-900 truncate" title="{{ $item->user->name ?? 'Pengguna' }}">{{ $item->user->name ?? 'Pengguna' }}</p>
                            <p class="text-xs text-slate-500 truncate" title="{{ $item->user->email ?? '-' }}">{{ $item->user->email ?? '-' }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4 border border-slate-200">
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Status Klaim</p>
                            <p class="font-semibold text-slate-900">{{ ucfirst(str_replace('_', ' ', $item->claim_status)) }}</p>
                            @if($item->claim_message)
                                <p class="text-xs text-slate-500 mt-2 line-clamp-3 hover:line-clamp-none cursor-pointer transition-all duration-300" title="Detail Bukti: {{ $item->claim_message }}">{{ $item->claim_message }}</p>
                            @endif
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4 border border-slate-200">
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Lokasi & Tanggal</p>
                            <p class="text-sm text-slate-900 truncate" title="{{ $item->lokasi }}">{{ $item->lokasi }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $item->tanggal_temu->format('d M Y') }}</p>
                        </div>
                        @if($item->status === 'ditemukan')
                            <div class="rounded-2xl bg-slate-50 p-4 border border-slate-200">
                                <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Penemu & Penyimpanan</p>
                                <p class="font-semibold text-slate-900">{{ $item->penemu ?: 'Belum dicatat' }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $item->penanganan === 'diserahkan_satpam' ? 'Dititipkan ke satpam' : ($item->penanganan === 'dibawa_penemu' ? 'Dibawa oleh penemu' : 'Status penyimpanan belum dicatat') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if(auth()->check() && (
                    (auth()->user()->role === 'admin' && ($item->claim_status === 'menunggu_verifikasi' || $item->status === 'diklaim')) ||
                    ($item->claim_status === 'ditemukan' && auth()->user()->id !== $item->user_id && auth()->user()->role !== 'admin') ||
                    (auth()->user()->role === 'admin' || auth()->user()->id === $item->user_id)
                ))
                <div class="border-t border-slate-100 pt-6">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Tindakan</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @if(auth()->user()->role === 'admin' && $item->claim_status === 'menunggu_verifikasi')
                            <form action="{{ route('items.claim.verify', $item->id) }}" method="POST" onsubmit="return confirm('Verifikasi klaim ini? Pastikan bukti kepemilikan sudah cocok.');" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-2xl transition shadow-sm hover:shadow cursor-pointer">Verifikasi Klaim</button>
                            </form>
                            <form action="{{ route('items.claim.reject', $item->id) }}" method="POST" onsubmit="return confirm('Tolak klaim ini?');" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 text-sm font-bold text-rose-600 bg-rose-50 rounded-2xl hover:bg-rose-100 transition border border-rose-250 cursor-pointer">Tolak Klaim</button>
                            </form>
                        @elseif(auth()->user()->role === 'admin' && $item->status === 'diklaim')
                            <form action="{{ route('items.unclaim', $item->id) }}" method="POST" onsubmit="return confirm('Batalkan klaim barang ini?');" class="w-full sm:col-span-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 text-sm font-bold text-amber-700 bg-amber-50 rounded-2xl hover:bg-amber-100 transition border border-amber-250 cursor-pointer">Batalkan Klaim</button>
                            </form>
                        @elseif($item->claim_status === 'ditemukan' && auth()->user()->id !== $item->user_id && auth()->user()->role !== 'admin')
                            <a href="#comment-form" class="w-full inline-flex items-center justify-center px-5 py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-2xl transition shadow-sm hover:shadow text-center sm:col-span-2">Buka Forum</a>
                        @endif

                        @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->id === $item->user_id))
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini? Tindakan ini tidak dapat dibatalkan.');" class="w-full sm:col-span-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-2xl transition shadow-sm hover:shadow cursor-pointer">Hapus Barang</button>
                            </form>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- FORUM PERCAKAPAN CARD -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Forum Forum Percakapan</h2>
                        <p class="text-sm text-slate-500">Diskusikan, tanyakan, atau konfirmasi sebelum mengklaim barang.</p>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-100">{{ $item->comments->count() }} komentar</span>
                </div>

                @if($item->comments->isEmpty())
                    <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-sm text-slate-500">
                        Belum ada percakapan. Jadilah yang pertama memberi komentar.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($item->comments as $comment)
                            <div class="rounded-3xl border border-slate-200 p-4 bg-slate-50">
                                <div class="flex items-center justify-between gap-3 mb-3">
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ $comment->user->name ?? 'Pengguna' }}</p>
                                        <p class="text-[11px] text-slate-500">{{ $comment->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <p class="text-sm text-slate-700 leading-relaxed">{{ $comment->message }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                @auth
                    <form id="comment-form" action="{{ route('items.comment', $item->id) }}" method="POST" class="mt-6 space-y-3">
                        @csrf
                        <div>
                            <label for="message" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Tambahkan Komentar</label>
                            <textarea name="message" id="message" rows="4" class="w-full rounded-3xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-200 focus:outline-none" placeholder="Tulis pesan singkat kepada pelapor atau calon klaim..."></textarea>
                            @error('message')
                                <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center px-5 py-3 text-sm font-bold text-white bg-indigo-600 rounded-2xl hover:bg-indigo-700 transition cursor-pointer">Kirim Komentar</button>
                        </div>
                    </form>
                @else
                    <div class="mt-6 rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center">
                        <p class="text-sm text-slate-600 mb-3">Masuk untuk ikut berdiskusi atau mengajukan klaim.</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-3 text-sm font-bold text-white bg-indigo-600 rounded-2xl hover:bg-indigo-700 transition">Masuk</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</body>
</html>
