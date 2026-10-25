<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item; // Menggunakan model Item
use Cloudinary\Cloudinary; // Pastikan class ini dipanggil

class LaporanController extends Controller
{
    // Method untuk menampilkan form (contoh)
    public function create()
    {
        return view('nama_view_form_anda');
    }

    // Method store ini yang dipanggil saat form disubmit
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        // Sesuaikan nama di sebelah kiri ('kategori', 'lokasi', dll) dengan atribut name="..." di HTML form Anda
        $request->validate([
            'nama_barang'   => 'required|string|max:255',
            'kategori'      => 'required|string',
            'lokasi'        => 'required|string',
            'tanggal_temu'  => 'required|date',
            'deskripsi'     => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // 2. Inisialisasi Cloudinary menggunakan URL dari .env
        $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

        // 3. Upload file fisik ke dalam folder 'laporan' di Cloudinary
        $uploadProses = $cloudinary->uploadApi()->upload($request->file('foto')->getRealPath(), [
            'folder' => 'laporan'
        ]);

        // 4. Ambil URL aman (HTTPS) dari hasil upload
        $uploadedFileUrl = $uploadProses['secure_url'];

        // 5. Simpan data ke database sesuai $fillable di Model Item
        Item::create([
            'user_id'      => auth()->id(), // Mengambil ID user yang sedang login (hapus baris ini jika fitur login belum ada)
            'nama_barang'  => $request->nama_barang,
            'kategori'     => $request->kategori,
            'lokasi'       => $request->lokasi,
            'tanggal_temu' => $request->tanggal_temu,
            'deskripsi'    => $request->deskripsi,
            'foto_path'    => $uploadedFileUrl, // Menggunakan foto_path, BUKAN foto_url
            'status'       => 'tersedia', // Anda bisa memberikan nilai default seperti ini
        ]);

        // 6. Alihkan halaman kembali (redirect) setelah berhasil
        return redirect()->back()->with('success', 'Laporan berhasil dikirim dan gambar tersimpan!');
    }
}