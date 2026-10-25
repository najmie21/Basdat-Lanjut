<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'nim')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('nim', 30)->nullable()->after('email');
            });
        }

        $users = DB::table('users')
            ->where('role', 'mahasiswa')
            ->whereNull('nim')
            ->get(['id', 'email']);

        $candidates = [];
        foreach ($users as $user) {
            if (preg_match('/^([0-9]{8,15})@untirta\.ac\.id$/i', $user->email, $matches)) {
                $candidates[$user->id] = $matches[1];
            }
        }

        $candidateCounts = array_count_values($candidates);
        foreach ($candidates as $userId => $nim) {
            if ($candidateCounts[$nim] === 1) {
                DB::table('users')->where('id', $userId)->update(['nim' => $nim]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unique('nim');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nim']);
            $table->dropColumn('nim');
        });
    }
};