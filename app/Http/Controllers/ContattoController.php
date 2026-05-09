<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Contatto;
use Illuminate\Http\Request;

class ContattoController extends Controller
{
    public function create(Cliente $cliente)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$cliente->users->contains($user->id)) {
            abort(403);
        }

        return view('contatti.create', compact('cliente'));
    }

    public function store(Request $request, Cliente $cliente)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$cliente->users->contains($user->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'ruolo' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
        ]);

        $validated['cliente_id'] = $cliente->id;
        Contatto::create($validated);

        session()->flash('success', 'Contatto aggiunto con successo.');
        return redirect()->route('clienti.show', $cliente->id);
    }

    public function edit(Cliente $cliente, Contatto $contatto)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$cliente->users->contains($user->id)) {
            abort(403);
        }

        return view('contatti.edit', compact('cliente', 'contatto'));
    }

    public function update(Request $request, Cliente $cliente, Contatto $contatto)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$cliente->users->contains($user->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'ruolo' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
        ]);

        $contatto->update($validated);

        session()->flash('success', 'Contatto aggiornato con successo.');
        return redirect()->route('clienti.show', $cliente->id);
    }

    public function destroy(Cliente $cliente, Contatto $contatto)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$cliente->users->contains($user->id)) {
            abort(403);
        }

        $contatto->delete();

        session()->flash('success', 'Contatto eliminato con successo.');
        return redirect()->route('clienti.show', $cliente->id);
    }
}
