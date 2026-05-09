<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['clienti', 'opportunita'])
            ->orderBy('name')
            ->paginate(20);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,agente'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        session()->flash('success', 'Utente creato con successo.');
        return redirect()->route('admin.users.index');
    }

    public function show($id)
    {
        $user = User::withCount(['clienti', 'opportunita', 'attivita'])
            ->with(['clienti' => fn($q) => $q->take(10)])
            ->findOrFail($id);

        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,agente'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        session()->flash('success', 'Utente aggiornato con successo.');
        return redirect()->route('admin.users.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'Non puoi eliminare il tuo account.');
            return redirect()->route('admin.users.index');
        }

        $user->delete();

        session()->flash('success', 'Utente eliminato con successo.');
        return redirect()->route('admin.users.index');
    }
}
