<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'user_id',
        'nama_barang',
        'kategori',
        'lokasi',
        'tanggal_temu',
        'deskripsi',
        'status',
        'penemu',
        'penanganan',
        'foto_path',
        'claimant_id',
        'claim_message',
        'claim_status',
        'claimed_at',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'tanggal_temu' => 'date',
        'claimed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function claimant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'claimant_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
}
