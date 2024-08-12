<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    public $fillable = [
        "user_id",
        "product_id",
        "transaction_id",
        "quantity",
        "total"
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

}
