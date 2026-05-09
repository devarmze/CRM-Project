<?php

namespace App\Http\Controllers;

use App\Models\Attivita;
use App\Models\Cliente;
use App\Models\Opportunita;
use Illuminate\Http\Request;

class AttivitaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = $user->isAdmin()
            ? Attivita::with(['user', 'cliente', 'opportunita'])
            : Attivita::where('user_id', $user->id)->with(['user', 'cliente', 'opportunita']);

        if ($request->filled('stato')) {
            $query->where('stato', $request->stato);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $attivita = $query->orderByDesc('data')->paginate(15)->withQueryString();

        return view('attivita.index', compact('attivita'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $clienti = $user->isAdmin()
            ? Cliente::orderBy('nome')->get()
            : $user->clienti()->orderBy('nome')->get();

        $opportunita = $user->isAdmin()
            ? Opportunita::with('cliente')->orderByDesc('created_at')->get()
            : Opportunita::where('user_id', $user->id)->with('cliente')->orderByDesc('created_at')->get();

        $cliente_id = $request->cliente_id;
        $opportunita_id = $request->opportunita_id;

        return view('attivita.create', compact('clienti', 'opportunita', 'cliente_id', 'opportunita_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => ['nullable', 'exists:clienti,id'],
            'opportunita_id' => ['nullable', 'exists:opportunita,id'],
            'tipo' => ['required', 'in:chiamata,email,incontro'],
            'data' => ['required', 'date'],
            'note' => ['nullable', 'string'],
            'stato' => ['required', 'in:da_fare,completata'],
        ]);

        $validated['user_id'] = auth()->id();
        $attivita = Attivita::create($validated);

        session()->flash('success', 'Attività creata con successo.');
        return redirect()->route('attivita.index');
    }

    public function show($id)
    {
        $user = auth()->user();
        $attivita = Attivita::with(['user', 'cliente', 'opportunita'])->findOrFail($id);

        if (!$user->isAdmin() && $attivita->user_id !== $user->id) {
            abort(403);
        }

        return view('attivita.show', compact('attivita'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $attivita = Attivita::findOrFail($id);

        if (!$user->isAdmin() && $attivita->user_id !== $user->id) {
            abort(403);
        }

        $clienti = $user->isAdmin()
            ? Cliente::orderBy('nome')->get()
            : $user->clienti()->orderBy('nome')->get();

        $opportunita = $user->isAdmin()
            ? Opportunita::with('cliente')->orderByDesc('created_at')->get()
            : Opportunita::where('user_id', $user->id)->with('cliente')->orderByDesc('created_at')->get();

        return view('attivita.edit', compact('attivita', 'clienti', 'opportunita'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $attivita = Attivita::findOrFail($id);

        if (!$user->isAdmin() && $attivita->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'cliente_id' => ['nullable', 'exists:clienti,id'],
            'opportunita_id' => ['nullable', 'exists:opportunita,id'],
            'tipo' => ['required', 'in:chiamata,email,incontro'],
            'data' => ['required', 'date'],
            'note' => ['nullable', 'string'],
            'stato' => ['required', 'in:da_fare,completata'],
        ]);

        $attivita->update($validated);

        session()->flash('success', 'Attività aggiornata con successo.');
        return redirect()->route('attivita.index');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $attivita = Attivita::findOrFail($id);

        if (!$user->isAdmin() && $attivita->user_id !== $user->id) {
            abort(403);
        }

        $attivita->delete();

        session()->flash('success', 'Attività eliminata con successo.');
        return redirect()->route('attivita.index');
    }
}
