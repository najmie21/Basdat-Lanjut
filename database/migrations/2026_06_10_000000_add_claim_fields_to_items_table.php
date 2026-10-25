<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('claimant_id')->nullable()->after('foto_path')->constrained('users')->nullOnDelete();
            $table->text('claim_message')->nullable()->after('claimant_id');
            $table->enum('claim_status', ['belum_diklaim', 'menunggu_verifikasi', 'terverifikasi', 'ditolak'])->default('belum_diklaim')->after('claim_message');
            $table->timestamp('claimed_at')->nullable()->after('claim_status');
            $table->foreignId('verified_by')->nullable()->after('claimed_at')->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('claimant_id');
            $table->dropColumn('claim_message');
            $table->dropColumn('claim_status');
            $table->dropColumn('claimed_at');
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn('verified_at');
        });
    }
};
