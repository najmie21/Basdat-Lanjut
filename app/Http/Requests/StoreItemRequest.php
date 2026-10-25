<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreItemRequest
 * 
 * Digunakan untuk memvalidasi input saat membuat laporan barang baru.
 * Memastikan data yang dikirim oleh pengguna aman dan sesuai dengan ketentuan tipe data.
 */
class StoreItemRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat request ini.
     * Untuk menyederhanakan, kita set ke true agar semua pengguna (atau user terautentikasi) bisa melapor.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Tentukan aturan validasi yang berlaku untuk request ini.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_barang' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string', 'max:255'],
            'tanggal_temu' => ['required', 'date', 'before_or_equal:today'],
            'deskripsi' => ['required', 'string'],
            'status' => ['required', 'string', 'in:hilang,ditemukan,diklaim'],
            'penemu' => ['required_if:status,ditemukan', 'nullable', 'string', 'max:255'],
            'penanganan' => ['required_if:status,ditemukan', 'nullable', 'in:dibawa_penemu,diserahkan_satpam'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'], // Maksimal 4MB
        ];
    }

    /**
     * Dapatkan pesan kesalahan kustom untuk aturan validasi yang ditentukan.
     * Pesan ini dalam Bahasa Indonesia untuk kemudahan pemahaman Tugas Akhir.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.max' => 'Nama barang tidak boleh lebih dari 255 karakter.',
            'kategori.required' => 'Kategori barang wajib diisi.',
            'lokasi.required' => 'Lokasi penemuan/kehilangan wajib diisi.',
            'tanggal_temu.required' => 'Tanggal kejadian wajib diisi.',
            'tanggal_temu.date' => 'Format tanggal tidak valid.',
            'tanggal_temu.before_or_equal' => 'Tanggal tidak boleh di masa depan.',
            'deskripsi.required' => 'Deskripsi barang wajib diisi.',
            'status.required' => 'Status laporan wajib ditentukan.',
            'status.in' => 'Status harus berupa: hilang, ditemukan, atau diklaim.',
            'penemu.required_if' => 'Nama penemu wajib diisi untuk barang temuan.',
            'penanganan.required_if' => 'Pilih siapa yang menyimpan barang temuan.',
            'penanganan.in' => 'Pilihan penyimpanan barang tidak valid.',
            'foto.image' => 'Berkas harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus berupa: jpg, jpeg, png, atau webp.',
            'foto.max' => 'Ukuran gambar maksimal adalah 4MB.',
        ];
    }
}
