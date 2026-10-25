<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel items.
     * Tabel ini menyimpan data barang hilang, ditemukan, dan diklaim.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke tabel users.
            // Jika user dihapus, data laporannya juga akan terhapus secara otomatis (cascade).
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
                  
            $table->string('nama_barang');
            $table->string('kategori');
            $table->string('lokasi');
            $table->date('tanggal_temu');
            $table->text('deskripsi');
            
            // Status barang menggunakan Enum sesuai permintaan: hilang, ditemukan, diklaim
            $table->enum('status', ['hilang', 'ditemukan', 'diklaim'])->default('ditemukan');
            
            // Path file foto barang yang diunggah. Nullable jika pelapor tidak menyertakan foto.
            $table->string('foto_path')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi (drop tabel items).
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
