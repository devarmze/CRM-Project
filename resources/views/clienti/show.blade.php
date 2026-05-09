@extends('layouts.app')

@section('title', $cliente->nome)
@section('page-title', $cliente->nome)

@section('content')
@php
    $statoColors = [
        'attivo' => 'bg-green-100 text-green-700',
        'prospect' => 'bg-blue-100 text-blue-700',
        'inattivo' => 'bg-gray-100 text-gray-500',
    ];
    $tipoColors = [
        'azienda' => 'bg-purple-100 text-purple-700',
        'privato' => 'bg-orange-100 text-orange-700',
    ];
    $faseColors = [
        'nuovo' => 'bg-blue-100 text-blue-700',
        'in_trattativa' => 'bg-yellow-100 text-yellow-700',
        'vinto' => 'bg-green-100 text-green-700',
        'perso' => 'bg-red-100 text-red-700',
    ];
    $isAssigned = $cliente->users->contains(auth()->id());
@endphp

{{-- Back --}}
<div class="mb-4">
    <a href="{{ route('clienti.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Torna ai clienti
    </a>
</div>

{{-- Header card --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                {{ strtoupper(substr($cliente->nome, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $cliente->nome }}</h2>
                <div class="flex flex-wrap gap-2 mt-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $tipoColors[$cliente->tipo] ?? '' }}">
                        {{ ucfirst($cliente->tipo) }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statoColors[$cliente->stato] ?? '' }}">
                        {{ ucfirst($cliente->stato) }}
                    </span>
                    @if($cliente->settore)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                        {{ $cliente->settore }}
                    </span>
                    @endif
                </div>
                {{-- Editor avatars --}}
                <div class="flex items-center gap-2 mt-3">
                    <span class="text-xs text-gray-500">Editor:</span>
                    <div class="flex -space-x-1">
                        @foreach($cliente->users as $u)
                        <div class="w-7 h-7 rounded-full bg-blue-500 border-2 border-white flex items-center justify-center text-white text-xs font-semibold"
                             title="{{ $u->name }}">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        @endforeach
                    </div>
                    @if($cliente->users->isEmpty())
                    <span class="text-xs text-gray-400">Nessuno assegnato</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Action buttons --}}
        <div class="flex flex-wrap gap-2 flex-shrink-0">
            @if($isAssigned)
            <form method="POST" action="{{ route('clienti.unassign', $cliente->id) }}">
                @csrf
                <button type="submit" class="px-4 py-2 text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-colors">
                    Rimuovimi dagli editor
                </button>
            </form>
            @else
            <form method="POST" action="{{ route('clienti.assign', $cliente->id) }}">
                @csrf
                <button type="submit" class="px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors">
                    Aggiungi me come editor
                </button>
            </form>
            @endif
            <a href="{{ route('clienti.edit', $cliente->id) }}"
               class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                Modifica
            </a>
        </div>
    </div>
</div>

{{-- Info + Contatti + Opportunita + Attivita + Timeline --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left col: info --}}
    <div class="space-y-5">
        {{-- Informazioni --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Informazioni</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Email</dt>
                    <dd class="text-sm text-gray-900 mt-0.5">{{ $cliente->email ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Telefono</dt>
                    <dd class="text-sm text-gray-900 mt-0.5">{{ $cliente->telefono ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Città</dt>
                    <dd class="text-sm text-gray-900 mt-0.5">{{ $cliente->citta ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Creato da</dt>
                    <dd class="text-sm text-gray-900 mt-0.5">{{ $cliente->creator->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Data creazione</dt>
                    <dd class="text-sm text-gray-900 mt-0.5">{{ $cliente->created_at->format('d/m/Y') }}</dd>
                </div>
            </dl>
        </div>

        {{-- Contatti --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-800">Contatti</h3>
                <a href="{{ route('clienti.contatti.create', $cliente->id) }}"
                   class="text-xs font-medium text-blue-600 hover:underline">+ Aggiungi</a>
            </div>
            @forelse($cliente->contatti as $contatto)
            <div class="flex items-start justify-between py-2.5 border-b border-gray-100 last:border-0">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $contatto->nome }}</p>
                    @if($contatto->ruolo)
                    <p class="text-xs text-gray-500">{{ $contatto->ruolo }}</p>
                    @endif
                    @if($contatto->email)
                    <p class="text-xs text-gray-400">{{ $contatto->email }}</p>
                    @endif
                    @if($contatto->telefono)
                    <p class="text-xs text-gray-400">{{ $contatto->telefono }}</p>
                    @endif
                </div>
                <div class="flex gap-1 ml-2 flex-shrink-0">
                    <a href="{{ route('clienti.contatti.edit', [$cliente->id, $contatto->id]) }}"
                       class="p-1 text-gray-400 hover:text-blue-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('clienti.contatti.destroy', [$cliente->id, $contatto->id]) }}"
                          onsubmit="return confirm('Eliminare il contatto?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1 text-gray-400 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 py-3 text-center">Nessun contatto.</p>
            @endforelse
        </div>
    </div>

    {{-- Right col --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Opportunità --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-800">Opportunità</h3>
                <a href="{{ route('opportunita.create', ['cliente_id' => $cliente->id]) }}"
                   class="text-xs font-medium text-blue-600 hover:underline">+ Aggiungi</a>
            </div>
            @forelse($cliente->opportunita as $opp)
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-100 last:border-0">
                <div class="flex-1 min-w-0">
                    <a href="{{ route('opportunita.show', $opp->id) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 truncate block">
                        {{ $opp->titolo }}
                    </a>
                    <p class="text-xs text-gray-400">{{ $opp->user->name ?? '-' }}
                        @if($opp->data_chiusura_prevista)
                         &middot; Chiusura: {{ $opp->data_chiusura_prevista->format('d/m/Y') }}
                        @endif
                    </p>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $faseColors[$opp->fase] ?? '' }}">
                    {{ ['nuovo'=>'Nuovo','in_trattativa'=>'In trattativa','vinto'=>'Vinto','perso'=>'Perso'][$opp->fase] ?? $opp->fase }}
                </span>
                <span class="text-sm font-semibold text-gray-700 whitespace-nowrap">€ {{ number_format($opp->valore_stimato, 0, ',', '.') }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 py-3 text-center">Nessuna opportunità.</p>
            @endforelse
        </div>

        {{-- Attività --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-800">Attività</h3>
                <a href="{{ route('attivita.create', ['cliente_id' => $cliente->id]) }}"
                   class="text-xs font-medium text-blue-600 hover:underline">+ Aggiungi</a>
            </div>
            @forelse($cliente->attivita->take(5) as $att)
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-100 last:border-0">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                    {{ $att->stato === 'completata' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ $att->stato === 'completata' ? 'Fatta' : 'Da fare' }}
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 capitalize">{{ $att->tipo }}</p>
                    <p class="text-xs text-gray-400">{{ $att->user->name ?? '-' }} &middot; {{ $att->data->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 py-3 text-center">Nessuna attività.</p>
            @endforelse
        </div>

        {{-- Timeline interazioni --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-800">Timeline Interazioni</h3>
                <a href="{{ route('clienti.interazioni.create', $cliente->id) }}"
                   class="text-xs font-medium text-blue-600 hover:underline">+ Registra</a>
            </div>
            @forelse($cliente->interazioni as $int)
            <div class="flex gap-4 pb-5 last:pb-0 relative">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 flex-shrink-0 z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    @if(!$loop->last)
                    <div class="w-0.5 flex-1 bg-gray-200 mt-1"></div>
                    @endif
                </div>
                <div class="flex-1 min-w-0 pt-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-900">{{ $int->tipo }}</p>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-400">{{ $int->data->format('d/m/Y H:i') }}</span>
                            <a href="{{ route('clienti.interazioni.edit', [$cliente->id, $int->id]) }}"
                               class="text-xs text-blue-600 hover:underline">Modifica</a>
                            <form method="POST" action="{{ route('clienti.interazioni.destroy', [$cliente->id, $int->id]) }}"
                                  onsubmit="return confirm('Eliminare l\'interazione?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:underline">Elimina</button>
                            </form>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $int->user->name ?? '-' }}</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $int->descrizione }}</p>
                    @if($int->esito)
                    <p class="text-xs text-gray-500 mt-1">Esito: {{ $int->esito }}</p>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 py-3 text-center">Nessuna interazione registrata.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
