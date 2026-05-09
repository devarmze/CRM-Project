@extends('layouts.app')

@section('title', 'Pool Clienti')
@section('page-title', 'Pool Clienti')

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
@endphp

<div class="mb-5">
    <p class="text-sm text-gray-500">Clienti non ancora assegnati a te. Puoi aggiungerli ai tuoi clienti.</p>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-5 shadow-sm">
    <form method="GET" action="{{ route('pool.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-gray-500 mb-1">Cerca</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nome, settore, città..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
        </div>
        <div class="w-40">
            <label class="block text-xs font-medium text-gray-500 mb-1">Stato</label>
            <select name="stato" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                <option value="">Tutti</option>
                <option value="attivo" {{ request('stato') === 'attivo' ? 'selected' : '' }}>Attivo</option>
                <option value="prospect" {{ request('stato') === 'prospect' ? 'selected' : '' }}>Prospect</option>
                <option value="inattivo" {{ request('stato') === 'inattivo' ? 'selected' : '' }}>Inattivo</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                Filtra
            </button>
            @if(request()->hasAny(['search','stato']))
            <a href="{{ route('pool.index') }}" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                Reset
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Cards grid --}}
@forelse($clienti as $cliente)
<div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-3 p-5">
    <div class="flex items-start justify-between gap-4">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center text-gray-600 font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr($cliente->nome, 0, 1)) }}
            </div>
            <div>
                <p class="text-base font-semibold text-gray-900">{{ $cliente->nome }}</p>
                <div class="flex flex-wrap gap-1.5 mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $tipoColors[$cliente->tipo] ?? '' }}">
                        {{ ucfirst($cliente->tipo) }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statoColors[$cliente->stato] ?? '' }}">
                        {{ ucfirst($cliente->stato) }}
                    </span>
                    @if($cliente->settore)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                        {{ $cliente->settore }}
                    </span>
                    @endif
                    @if($cliente->citta)
                    <span class="text-xs text-gray-400">{{ $cliente->citta }}</span>
                    @endif
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    Creato da: {{ $cliente->creator->name ?? '-' }}
                    @if($cliente->users->count())
                     &middot; Assegnato a: {{ $cliente->users->pluck('name')->join(', ') }}
                    @endif
                </div>
            </div>
        </div>

        <div class="flex gap-2 flex-shrink-0">
            <form method="POST" action="{{ route('clienti.assign', $cliente->id) }}">
                @csrf
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    Aggiungi ai miei clienti
                </button>
            </form>
            <a href="{{ route('clienti.show', $cliente->id) }}"
               class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                Visualizza
            </a>
        </div>
    </div>
</div>
@empty
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-10 text-center">
    <p class="text-gray-400">Nessun cliente disponibile nel pool.</p>
    <p class="text-sm text-gray-400 mt-1">Tutti i clienti ti sono già assegnati oppure non esistono ancora clienti.</p>
</div>
@endforelse

@if($clienti->hasPages())
<div class="mt-4">
    {{ $clienti->links() }}
</div>
@endif
@endsection
