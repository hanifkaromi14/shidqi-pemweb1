<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())->latest()->get();
        return view('wishlists.index', compact('wishlists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name'        => 'required|string|max:100',
            'priority'         => 'required|in:Tinggi,Sedang,Rendah',
            'estimated_cost'   => 'required|numeric|min:0',
            'collected_amount' => 'required|numeric|min:0',
            'status'           => 'required|in:Belum Mulai,Sedang Dicicil,Tercapai',
            'notes'            => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();
        Wishlist::create($validated);

        return redirect()->route('wishlists.index')->with('success', 'Item target wishlist berhasil ditambahkan.');
    }

    public function destroy(Wishlist $wishlist)
    {
        abort_if($wishlist->user_id !== Auth::id(), 403);
        $wishlist->delete();

        return redirect()->route('wishlists.index')->with('success', 'Item target wishlist berhasil dihapus.');
    }
}
