<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    /**
     * Tampilkan daftar negara favorit user
     */
    public function index()
    {
        $favorites = Watchlist::with('country')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('favorites.index', compact('favorites'));
    }

    /**
     * API untuk toggle (tambah/hapus) negara dari favorit
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
        ]);

        $userId = Auth::id();
        $countryId = $request->country_id;

        $watchlist = Watchlist::where('user_id', $userId)
            ->where('country_id', $countryId)
            ->first();

        if ($watchlist) {
            // Jika sudah ada, maka hapus (unfavorite)
            $watchlist->delete();
            return response()->json([
                'success' => true,
                'status' => 'removed',
                'message' => 'Negara dihapus dari favorit.'
            ]);
        } else {
            // Jika belum ada, maka tambahkan (favorite)
            Watchlist::create([
                'user_id' => $userId,
                'country_id' => $countryId,
            ]);
            return response()->json([
                'success' => true,
                'status' => 'added',
                'message' => 'Negara ditambahkan ke favorit.'
            ]);
        }
    }
}
