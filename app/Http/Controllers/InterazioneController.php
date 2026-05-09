<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Interazione;
use Illuminate\Http\Request;

class InterazioneController extends Controller
{
    public function create(Cliente $cliente)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$cliente->users->contains($user->id)) {
            abort(403);
        }

        return view('interazioni.create', compact('cliente'));
    }

    public function store(Request $request, Cliente $cliente)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$cliente->users->contains($user->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'data' => ['required', 'date'],
            'tipo' => ['required', 'string', 'max:100'],
            'descrizione' => ['required', 'string'],
            'esito' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['cliente_id'] = $cliente->id;
        $validated['user_id'] = auth()->id();

        Interazione::create($validated);

        session()->flash('success', 'Interazione registrata con successo.');
        return redirect()->route('clienti.show', $cliente->id);
    }

    public function edit(Cliente $cliente, Interazione $interazione)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$cliente->users->contains($user->id)) {
            abort(403);
        }

        return view('interazioni.edit', compact('cliente', 'interazione'));
    }

    public function update(Request $request, Cliente $cliente, Interazione $interazione)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$cliente->users->contains($user->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'data' => ['required', 'date'],
            'tipo' => ['required', 'string', 'max:100'],
            'descrizione' => ['required', 'string'],
            'esito' => ['nullable', 'string', 'max:255'],
        ]);

        $interazione->update($validated);

        session()->flash('success', 'Interazione aggiornata con successo.');
        return redirect()->route('clienti.show', $cliente->id);
    }

    public function destroy(Cliente $cliente, Interazione $interazione)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$cliente->users->contains($user->id)) {
            abort(403);
        }

        $interazione->delete();

        session()->flash('success', 'Interazione eliminata con successo.');
        return redirect()->route('clienti.show', $cliente->id);
    }
}
