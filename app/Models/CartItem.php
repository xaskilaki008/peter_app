<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'listing_id'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
