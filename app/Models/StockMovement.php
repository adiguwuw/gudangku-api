<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'transaction_id',
        'type',
        'quantity',
        'reference_type',
        'notes',
    ];

    // RELATION
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function transaction()
    {
        return $this->belongsTo(StockTransaction::class, 'transaction_id');
    }
}