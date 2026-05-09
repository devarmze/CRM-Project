<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Cliente::with(['users', 'creator']);

        if (!$user->isAdmin()) {
            $assignedIds = $user->clienti()->pluck('clienti.id');
            $query->whereIn('id', $assignedIds);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('settore', 'like', "%{$search}%");
            });
        }

        if ($request->filled('stato')) {
            $query->where('stato', $request->stato);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $clienti = $query->orderBy('nome')->paginate(15)->withQueryString();

        return view('clienti.index', compact('clienti'));
    }

    public function show($id)
    {
        $user = auth()->user();
        $cliente = Cliente::with(['users', 'creator', 'contatti', 'opportunita.user', 'attivita.user', 'interazioni.user'])
            ->findOrFail($id);

        if (!$user->isAdmin() && !$cliente->users->contains($user->id)) {
            abort(403);
        }

        return view('clienti.show', compact('cliente'));
    }

    public function create()
    {
        return view('clienti.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'in:azienda,privato'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'citta' => ['nullable', 'string', 'max:100'],
            'settore' => ['nullable', 'string', 'max:100'],
            'stato' => ['required', 'in:attivo,prospect,inattivo'],
        ]);

        $validated['created_by'] = auth()->id();
        $cliente = Cliente::create($validated);

        $cliente->users()->attach(auth()->id());

        session()->flash('success', 'Cliente creato con successo.');
        return redirect()->route('clienti.show', $cliente->id);
    }

    public function edit($id)
    {
        $user = auth()->user();
        $cliente = Cliente::findOrFail($id);

        if (!$user->isAdmin() && !$cliente->users->contains($user->id)) {
            abort(403);
        }

        return view('clienti.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $cliente = Cliente::findOrFail($id);

        if (!$user->isAdmin() && !$cliente->users->contains($user->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'in:azienda,privato'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'citta' => ['nullable', 'string', 'max:100'],
            'settore' => ['nullable', 'string', 'max:100'],
            'stato' => ['required', 'in:attivo,prospect,inattivo'],
        ]);

        $cliente->update($validated);

        session()->flash('success', 'Cliente aggiornato con successo.');
        return redirect()->route('clienti.show', $cliente->id);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $cliente = Cliente::findOrFail($id);

        if (!$user->isAdmin() && $cliente->created_by !== $user->id) {
            abort(403);
        }

        $cliente->delete();

        session()->flash('success', 'Cliente eliminato con successo.');
        return redirect()->route('clienti.index');
    }

    public function assign($id)
    {
        $cliente = Cliente::findOrFail($id);
        $user = auth()->user();

        if (!$cliente->users->contains($user->id)) {
            $cliente->users()->attach($user->id);
            session()->flash('success', 'Sei stato aggiunto come editor del cliente.');
        } else {
            session()->flash('error', 'Sei già assegnato a questo cliente.');
        }

        return redirect()->back();
    }

    public function unassign($id)
    {
        $cliente = Cliente::findOrFail($id);
        $user = auth()->user();

        $cliente->users()->detach($user->id);
        session()->flash('success', 'Sei stato rimosso dagli editor del cliente.');

        return redirect()->back();
    }
}
