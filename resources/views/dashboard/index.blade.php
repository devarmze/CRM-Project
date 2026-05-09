@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

@if(auth()->user()->isAdmin())
{{-- ===================== ADMIN DASHBOARD ===================== --}}

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Clienti totali</span>
            <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['totale_clienti'] }}</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Opportunità</span>
            <div class="w-9 h-9 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['totale_opportunita'] }}</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Attività</span>
            <div class="w-9 h-9 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['totale_attivita'] }}</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Interazioni</span>
            <div class="w-9 h-9 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['totale_interazioni'] }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Pipeline per fase --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Pipeline per Fase</h2>
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
        @forelse($pipeline as $item)
        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $faseColors[$item->fase] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $faseLabels[$item->fase] ?? $item->fase }}
                </span>
                <span class="text-sm text-gray-600">{{ $item->totale }} {{ $item->totale == 1 ? 'opportunità' : 'opportunità' }}</span>
            </div>
            <span class="text-sm font-semibold text-gray-900">€ {{ number_format($item->valore_totale, 0, ',', '.') }}</span>
        </div>
        @empty
        <p class="text-sm text-gray-400 py-4 text-center">Nessuna opportunità presente.</p>
        @endforelse
    </div>

    {{-- Top agenti --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Top Agenti per Valore</h2>
        @forelse($top_agenti as $i => $agente)
        <div class="flex items-center gap-3 py-2.5 border-b border-gray-100 last:border-0">
            <span class="w-6 h-6 rounded-full bg-gray-100 text-xs font-bold text-gray-500 flex items-center justify-center">{{ $i + 1 }}</span>
            <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-semibold">
                {{ strtoupper(substr($agente->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ $agente->name }}</p>
                <p class="text-xs text-gray-400">{{ $agente->opportunita_count }} opportunità</p>
            </div>
            <span class="text-sm font-semibold text-gray-900">€ {{ number_format($agente->opportunita_sum_valore_stimato ?? 0, 0, ',', '.') }}</span>
        </div>
        @empty
        <p class="text-sm text-gray-400 py-4 text-center">Nessun agente presente.</p>
        @endforelse
    </div>
</div>

{{-- Attività recenti --}}
<div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-gray-800">Attività Recenti</h2>
        <a href="{{ route('attivita.index') }}" class="text-sm text-blue-600 hover:underline">Vedi tutte</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-2 pr-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="text-left py-2 pr-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Agente</th>
                    <th class="text-left py-2 pr-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="text-left py-2 pr-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Data</th>
                    <th class="text-left py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stato</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attivita_recenti as $attivita)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="py-2.5 pr-4 capitalize text-gray-700">{{ $attivita->tipo }}</td>
                    <td class="py-2.5 pr-4 text-gray-600">{{ $attivita->user->name ?? '-' }}</td>
                    <td class="py-2.5 pr-4 text-gray-600">{{ $attivita->cliente->nome ?? '-' }}</td>
                    <td class="py-2.5 pr-4 text-gray-500">{{ $attivita->data->format('d/m/Y H:i') }}</td>
                    <td class="py-2.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            {{ $attivita->stato === 'completata' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $attivita->stato === 'completata' ? 'Completata' : 'Da fare' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-gray-400">Nessuna attività presente.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@else
{{-- ===================== AGENTE DASHBOARD ===================== --}}

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Miei clienti</span>
            <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $miei_clienti_count }}</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Opportunità aperte</span>
            <div class="w-9 h-9 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $opportunita_aperte }}</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Attività oggi</span>
            <div class="w-9 h-9 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $attivita_oggi->count() }}</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Valore pipeline</span>
            <div class="w-9 h-9 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">€ {{ number_format($pipeline_value, 0, ',', '.') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Attività di oggi --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-gray-800">Attività di Oggi</h2>
            <a href="{{ route('attivita.create') }}" class="text-sm text-blue-600 hover:underline">+ Nuova</a>
        </div>
        @forelse($attivita_oggi as $att)
        <div class="flex items-start gap-3 py-3 border-b border-gray-100 last:border-0">
            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                {{ $att->stato === 'completata' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                {{ $att->stato === 'completata' ? 'Fatta' : 'Da fare' }}
            </span>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 capitalize">{{ $att->tipo }}</p>
                @if($att->cliente)
                <p class="text-xs text-gray-500">{{ $att->cliente->nome }}</p>
                @endif
                <p class="text-xs text-gray-400">{{ $att->data->format('H:i') }}</p>
            </div>
        </div>
        @empty
        <p class="text-sm text-gray-400 py-4 text-center">Nessuna attività pianificata per oggi.</p>
        @endforelse
    </div>

    {{-- Opportunità recenti --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
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
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-gray-800">Le Mie Opportunità</h2>
            <a href="{{ route('opportunita.index') }}" class="text-sm text-blue-600 hover:underline">Vedi tutte</a>
        </div>
        @forelse($opportunita_recenti as $opp)
        <div class="flex items-center gap-3 py-3 border-b border-gray-100 last:border-0">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ $opp->titolo }}</p>
                <p class="text-xs text-gray-500">{{ $opp->cliente->nome ?? '-' }}</p>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $faseColors[$opp->fase] ?? 'bg-gray-100 text-gray-700' }}">
                {{ $faseLabels[$opp->fase] ?? $opp->fase }}
            </span>
            <span class="text-sm font-semibold text-gray-700">€ {{ number_format($opp->valore_stimato, 0, ',', '.') }}</span>
        </div>
        @empty
        <p class="text-sm text-gray-400 py-4 text-center">Nessuna opportunità presente.</p>
        @endforelse
    </div>
</div>

@endif
@endsection
