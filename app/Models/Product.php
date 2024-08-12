<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Favourite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public $fillable = [
        "name",
        "price",
        "discount",
        "image",
        "brand",
        "category",
        "description"
    ];


    public function cart(){
        return $this->hasMany(Cart::class);
    }


    public function hasCart(User $user){
        return $this->cart->contains("user_id", $user->id);
    }


    public function favourite(){
        return $this->hasMany(Favourite::class);
    }


    public function hasFavourite(User $user){
        return $this->favourite->contains("user_id", $user->id);
    }



    public function order(){
        return $this->hasMany(Order::class);
    }

    public function hasOrder(User $user){
        return $this->favourite->contains("user_id", $user->id);
    }


}
