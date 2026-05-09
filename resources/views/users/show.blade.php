@extends('layouts.app')

@section('title', $user->name)
@section('page-title', 'Dettaglio Utente')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Torna agli utenti
    </a>
</div>

<div class="max-w-2xl space-y-5">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center gap-4 mb-5">
            <div class="w-14 h-14 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium mt-1
                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ $user->role === 'admin' ? 'Amministratore' : 'Agente' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-5 pt-5 border-t border-gray-100">
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $user->clienti_count }}</p>
                <p class="text-xs text-gray-500 mt-1">Clienti assegnati</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $user->opportunita_count }}</p>
                <p class="text-xs text-gray-500 mt-1">Opportunità</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $user->attivita_count }}</p>
                <p class="text-xs text-gray-500 mt-1">Attività</p>
            </div>
        </div>

        <div class="flex gap-3 mt-5 pt-5 border-t border-gray-100">
            <a href="{{ route('admin.users.edit', $user->id) }}"
               class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                Modifica
            </a>
        </div>
    </div>

    @if($user->clienti->count())
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Clienti assegnati</h3>
        <div class="space-y-2">
            @foreach($user->clienti as $c)
            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                <p class="text-sm font-medium text-gray-900">{{ $c->nome }}</p>
                <a href="{{ route('clienti.show', $c->id) }}" class="text-xs text-blue-600 hover:underline">Visualizza</a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
