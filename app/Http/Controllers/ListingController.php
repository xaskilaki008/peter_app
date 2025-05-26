<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }

        $listings = $query->paginate(6)->withQueryString();

        return view('listings.index', compact('listings'));
    }


    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'season' => 'required|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Обработка фото
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo'] = $path;
        } else {
            $validated['photo'] = null;
        }

        Listing::create($validated);
        return response()->json(['message' => 'Объявление создано']);
    }

    public function show($id)
    {
        $listing = Listing::findOrFail($id);
        return response()->json($listing);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        $listing = Listing::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'season' => 'required|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Обновление фото
        if ($request->hasFile('photo')) {
            if ($listing->photo) {
                Storage::disk('public')->delete($listing->photo);
            }
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo'] = $path;
        } else {
            // Если фото не загружали, оставить существующее
            $validated['photo'] = $listing->photo;
        }

        $listing->update($validated);

        return response()->json(['message' => 'Объявление обновлено']);
    }

    public function destroy($id)
    {
        $this->authorizeAdmin();

        $listing = Listing::findOrFail($id);

        if ($listing->photo) {
            Storage::disk('public')->delete($listing->photo);
        }

        $listing->delete();
        return response()->json(['message' => 'Объявление удалено']);
    }

    private function authorizeAdmin()
    {
        if (Auth::user()?->role !== 'Администратор') {
            abort(403, 'Доступ запрещён');
        }
    }

    public function admin()
    {
        $this->authorizeAdmin();

        $listings = Listing::paginate(5);
        return view('listings.admin', compact('listings'));
    }
}
