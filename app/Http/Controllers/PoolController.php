<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class PoolController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $assignedIds = $user->clienti()->pluck('clienti.id');

        $query = Cliente::whereNotIn('id', $assignedIds)->with(['users', 'creator']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('settore', 'like', "%{$search}%")
                  ->orWhere('citta', 'like', "%{$search}%");
            });
        }

        if ($request->filled('stato')) {
            $query->where('stato', $request->stato);
        }

        $clienti = $query->orderBy('nome')->paginate(15)->withQueryString();

        return view('pool.index', compact('clienti'));
    }
}
