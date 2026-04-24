<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'item_id', 'quantity'];

    /**
     * Relasi: Transaksi ini dimiliki oleh siapa?
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Relasi: Transaksi ini mencatat pembelian barang apa?
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
