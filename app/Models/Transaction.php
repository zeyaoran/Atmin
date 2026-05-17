<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Ticket;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_user',
        'id_tiket',
        'jumlah',
        'total_harga',
        'tanggal_transaksi',
        'metode_pembayaran',
        'status_pembayaran',
        'payment_reference',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'id_tiket');
    }

    public function getRouteKeyName()
    {
        return 'id_transaksi';
    }
}