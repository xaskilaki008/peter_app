<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Показать корзину
    public function index()
    {
        $user = auth()->user();

        $cart = $user->cart()->with('items.listing')->first();

        $items = $cart ? $cart->items : collect();

        //Общая стоимость
        $totalPrice = $items->sum(function ($item) {
            return $item->listing->price;
        });

        return view('cart.index', compact('items', 'totalPrice'));
    }


    // Добавить товар в корзину
    public function add(Listing $listing)
    {
        $user = Auth::user();

        // Получить корзину или создать новую
        $cart = $user->cart()->firstOrCreate([]);

        $exists = $cart->items()->where('listing_id', $listing->id)->exists();

        if (!$exists) {
            $cart->items()->create(['listing_id' => $listing->id]);
        }

        return redirect()->route('cart.index')->with('success', 'Товар добавлен в корзину.');
    }

    // Удалить товар из корзины
    public function remove(Listing $listing)
    {
        $user = Auth::user();
        $cart = $user->cart;

        if ($cart) {
            $cart->items()->where('listing_id', $listing->id)->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Товар удалён из корзины.');
    }
}
