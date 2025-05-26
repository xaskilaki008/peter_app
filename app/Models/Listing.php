<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = ['title', 'brand', 'size', 'quantity', 'price', 'season', 'photo'];
}
