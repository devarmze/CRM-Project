@extends('layouts.app')

@section('title', 'Clienti')
@section('page-title', 'Clienti')

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

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-gray-500">{{ $clienti->total() }} clienti trovati</p>
    </div>
    <a href="{{ route('clienti.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuovo cliente
    </a>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-5 shadow-sm">
    <form method="GET" action="{{ route('clienti.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-gray-500 mb-1">Cerca</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nome o settore..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
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
        <div class="w-40">
            <label class="block text-xs font-medium text-gray-500 mb-1">Tipo</label>
            <select name="tipo" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                <option value="">Tutti</option>
                <option value="azienda" {{ request('tipo') === 'azienda' ? 'selected' : '' }}>Azienda</option>
                <option value="privato" {{ request('tipo') === 'privato' ? 'selected' : '' }}>Privato</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                Filtra
            </button>
            @if(request()->hasAny(['search','stato','tipo']))
            <a href="{{ route('clienti.index') }}" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                Reset
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nome</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Settore</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stato</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Città</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Editor</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Azioni</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($clienti as $cliente)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <a href="{{ route('clienti.show', $cliente->id) }}" class="font-medium text-gray-900 hover:text-blue-600">
                            {{ $cliente->nome }}
                        </a>
                        @if($cliente->email)
                        <p class="text-xs text-gray-400">{{ $cliente->email }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $tipoColors[$cliente->tipo] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($cliente->tipo) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $cliente->settore ?? '-' }}</td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statoColors[$cliente->stato] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($cliente->stato) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $cliente->citta ?? '-' }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex -space-x-1">
                            @foreach($cliente->users->take(3) as $u)
                            <div class="w-7 h-7 rounded-full bg-blue-500 border-2 border-white flex items-center justify-center text-white text-xs font-semibold"
                                 title="{{ $u->name }}">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            @endforeach
                            @if($cliente->users->count() > 3)
                            <div class="w-7 h-7 rounded-full bg-gray-300 border-2 border-white flex items-center justify-center text-gray-600 text-xs font-semibold">
                                +{{ $cliente->users->count() - 3 }}
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('clienti.show', $cliente->id) }}"
                               class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                Visualizza
                            </a>
                            <a href="{{ route('clienti.edit', $cliente->id) }}"
                               class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                Modifica
                            </a>
                            @if(auth()->user()->isAdmin() || $cliente->created_by === auth()->id())
                            <form method="POST" action="{{ route('clienti.destroy', $cliente->id) }}"
                                  onsubmit="return confirm('Eliminare definitivamente il cliente {{ addslashes($cliente->nome) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                    Elimina
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-gray-400">
                        Nessun cliente trovato.
                        <a href="{{ route('clienti.create') }}" class="text-blue-600 hover:underline ml-1">Crea il primo</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clienti->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $clienti->links() }}
    </div>
    @endif
</div>
@endsection
