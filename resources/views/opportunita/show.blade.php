@extends('layouts.app')

@section('title', $opportunita->titolo)
@section('page-title', 'Dettaglio Opportunità')

@section('content')
@php
    $faseColors = [
        'nuovo' => 'bg-blue-100 text-blue-700',
        'in_trattativa' => 'bg-yellow-100 text-yellow-700',
        'vinto' => 'bg-green-100 text-green-700',
        'perso' => 'bg-red-100 text-red-700',
    ];
    $faseLabels = [
        'nuovo' => 'Nuovo',
        'in_trattativa' => 'In trattativa',
        'vinto' => 'Vinto',
        'perso' => 'Perso',
    ];
@endphp

<div class="mb-4">
    <a href="{{ route('opportunita.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Torna alle opportunità
    </a>
</div>

<div class="max-w-3xl space-y-5">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $opportunita->titolo }}</h2>
                <div class="flex flex-wrap gap-2 mt-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $faseColors[$opportunita->fase] ?? '' }}">
                        {{ $faseLabels[$opportunita->fase] ?? $opportunita->fase }}
                    </span>
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="text-3xl font-bold text-gray-900">€ {{ number_format($opportunita->valore_stimato, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-1">Valore stimato</p>
            </div>
        </div>

        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-6 pt-5 border-t border-gray-100">
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase">Cliente</dt>
                <dd class="mt-1">
                    <a href="{{ route('clienti.show', $opportunita->cliente_id) }}" class="text-sm text-blue-600 hover:underline font-medium">
                        {{ $opportunita->cliente->nome ?? '-' }}
                    </a>
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase">Agente</dt>
                <dd class="text-sm text-gray-900 mt-1">{{ $opportunita->user->name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase">Chiusura prevista</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    {{ $opportunita->data_chiusura_prevista ? $opportunita->data_chiusura_prevista->format('d/m/Y') : '—' }}
                </dd>
            </div>
        </dl>

        @if($opportunita->note)
        <div class="mt-5 pt-5 border-t border-gray-100">
            <dt class="text-xs font-medium text-gray-500 uppercase mb-2">Note</dt>
            <p class="text-sm text-gray-700">{{ $opportunita->note }}</p>
        </div>
        @endif

        <div class="flex gap-3 mt-6 pt-5 border-t border-gray-100">
            <a href="{{ route('opportunita.edit', $opportunita->id) }}"
               class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                Modifica
            </a>
            <form method="POST" action="{{ route('opportunita.destroy', $opportunita->id) }}"
                  onsubmit="return confirm('Eliminare questa opportunità?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-5 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-sm font-medium rounded-lg transition-colors border border-red-200">
                    Elimina
                </button>
            </form>
        </div>
    </div>

    {{-- Attività collegate --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-gray-800">Attività collegate</h3>
            <a href="{{ route('attivita.create', ['opportunita_id' => $opportunita->id]) }}"
               class="text-xs font-medium text-blue-600 hover:underline">+ Aggiungi</a>
        </div>
        @forelse($opportunita->attivita as $att)
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
        <p class="text-sm text-gray-400 py-3 text-center">Nessuna attività collegata.</p>
        @endforelse
    </div>
</div>
@endsection
