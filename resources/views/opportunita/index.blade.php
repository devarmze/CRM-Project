@extends('layouts.app')

@section('title', 'Opportunità')
@section('page-title', 'Opportunità')

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

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $opportunita->total() }} opportunità trovate</p>
    <a href="{{ route('opportunita.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuova opportunità
    </a>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-5 shadow-sm">
    <form method="GET" action="{{ route('opportunita.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-gray-500 mb-1">Cerca titolo</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Titolo opportunità..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
        </div>
        <div class="w-44">
            <label class="block text-xs font-medium text-gray-500 mb-1">Fase</label>
            <select name="fase" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                <option value="">Tutte le fasi</option>
                @foreach($faseLabels as $key => $label)
                <option value="{{ $key }}" {{ request('fase') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                Filtra
            </button>
            @if(request()->hasAny(['search','fase']))
            <a href="{{ route('opportunita.index') }}" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                Reset
            </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Titolo</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Agente</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fase</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Valore</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Chiusura prev.</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Azioni</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($opportunita as $opp)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <a href="{{ route('opportunita.show', $opp->id) }}" class="font-medium text-gray-900 hover:text-blue-600">
                            {{ $opp->titolo }}
                        </a>
                    </td>
                    <td class="px-5 py-3.5 text-gray-600">
                        <a href="{{ route('clienti.show', $opp->cliente_id) }}" class="hover:text-blue-600">
                            {{ $opp->cliente->nome ?? '-' }}
                        </a>
                    </td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $opp->user->name ?? '-' }}</td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $faseColors[$opp->fase] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $faseLabels[$opp->fase] ?? $opp->fase }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 font-semibold text-gray-900">€ {{ number_format($opp->valore_stimato, 0, ',', '.') }}</td>
                    <td class="px-5 py-3.5 text-gray-500">
                        {{ $opp->data_chiusura_prevista ? $opp->data_chiusura_prevista->format('d/m/Y') : '—' }}
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('opportunita.show', $opp->id) }}"
                               class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                Visualizza
                            </a>
                            <a href="{{ route('opportunita.edit', $opp->id) }}"
                               class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                Modifica
                            </a>
                            <form method="POST" action="{{ route('opportunita.destroy', $opp->id) }}"
                                  onsubmit="return confirm('Eliminare questa opportunità?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                    Elimina
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-gray-400">
                        Nessuna opportunità trovata.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($opportunita->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $opportunita->links() }}
    </div>
    @endif
</div>
@endsection
