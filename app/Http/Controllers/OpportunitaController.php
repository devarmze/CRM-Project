<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Opportunita;
use Illuminate\Http\Request;

class OpportunitaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = $user->isAdmin()
            ? Opportunita::with(['cliente', 'user'])
            : Opportunita::where('user_id', $user->id)->with(['cliente', 'user']);

        if ($request->filled('fase')) {
            $query->where('fase', $request->fase);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('titolo', 'like', "%{$search}%");
        }

        $opportunita = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('opportunita.index', compact('opportunita'));
    }

    public function create(Request $request)
    {
        $clienti = auth()->user()->isAdmin()
            ? Cliente::orderBy('nome')->get()
            : auth()->user()->clienti()->orderBy('nome')->get();

        $cliente_id = $request->cliente_id;
        return view('opportunita.create', compact('clienti', 'cliente_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => ['required', 'exists:clienti,id'],
            'titolo' => ['required', 'string', 'max:255'],
            'valore_stimato' => ['required', 'numeric', 'min:0'],
            'fase' => ['required', 'in:nuovo,in_trattativa,vinto,perso'],
            'data_chiusura_prevista' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        $validated['user_id'] = auth()->id();
        $opportunita = Opportunita::create($validated);

        session()->flash('success', 'Opportunità creata con successo.');
        return redirect()->route('opportunita.show', $opportunita->id);
    }

    public function show($id)
    {
        $user = auth()->user();
        $opportunita = Opportunita::with(['cliente', 'user', 'attivita.user'])->findOrFail($id);

        if (!$user->isAdmin() && $opportunita->user_id !== $user->id) {
            abort(403);
        }

        return view('opportunita.show', compact('opportunita'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $opportunita = Opportunita::findOrFail($id);

        if (!$user->isAdmin() && $opportunita->user_id !== $user->id) {
            abort(403);
        }

        $clienti = $user->isAdmin()
            ? Cliente::orderBy('nome')->get()
            : $user->clienti()->orderBy('nome')->get();

        return view('opportunita.edit', compact('opportunita', 'clienti'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $opportunita = Opportunita::findOrFail($id);

        if (!$user->isAdmin() && $opportunita->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'cliente_id' => ['required', 'exists:clienti,id'],
            'titolo' => ['required', 'string', 'max:255'],
            'valore_stimato' => ['required', 'numeric', 'min:0'],
            'fase' => ['required', 'in:nuovo,in_trattativa,vinto,perso'],
            'data_chiusura_prevista' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        $opportunita->update($validated);

        session()->flash('success', 'Opportunità aggiornata con successo.');
        return redirect()->route('opportunita.show', $opportunita->id);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $opportunita = Opportunita::findOrFail($id);

        if (!$user->isAdmin() && $opportunita->user_id !== $user->id) {
            abort(403);
        }

        $opportunita->delete();

        session()->flash('success', 'Opportunità eliminata con successo.');
        return redirect()->route('opportunita.index');
    }
}
