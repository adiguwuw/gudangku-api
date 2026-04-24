<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class StockTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'type',
        'source',
        'notes',
    ];

    // 🔥 RELATIONSHIP

    public function items()
    {
        return $this->hasMany(StockTransactionItem::class, 'transaction_id');
    }

    // 🔥 AUTO GENERATE TRANSACTION CODE (ANTI DUPLICATE)
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            // hanya generate kalau kosong
            if (!$model->transaction_code) {
                $model->transaction_code = self::generateUniqueCode();
            }
        });
    }

    // 🔥 GENERATOR CODE AMAN
    private static function generateUniqueCode(): string
    {
        do {
            $code = 'TRX-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(5));
        } while (self::where('transaction_code', $code)->exists());

        return $code;
    }

    // 🔥 HELPER (OPTIONAL - BAGUS BANGET BUAT UI)

    public function isIn(): bool
    {
        return $this->type === 'IN';
    }

    public function isOut(): bool
    {
        return $this->type === 'OUT';
    }
}