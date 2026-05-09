@extends('layouts.app')

@section('title', 'Dettaglio Attività')
@section('page-title', 'Dettaglio Attività')

@section('content')
<div class="mb-4">
    <a href="{{ route('attivita.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Torna alle attività
    </a>
</div>

<div class="max-w-xl bg-white rounded-xl border border-gray-200 shadow-sm p-6">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-bold text-gray-900 capitalize">{{ $attivita->tipo }}</h2>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
            {{ $attivita->stato === 'completata' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
            {{ $attivita->stato === 'completata' ? 'Completata' : 'Da fare' }}
        </span>
    </div>

    <dl class="space-y-4">
        <div>
            <dt class="text-xs font-medium text-gray-500 uppercase">Data</dt>
            <dd class="text-sm text-gray-900 mt-1">{{ $attivita->data->format('d/m/Y H:i') }}</dd>
        </div>
        <div>
            <dt class="text-xs font-medium text-gray-500 uppercase">Agente</dt>
            <dd class="text-sm text-gray-900 mt-1">{{ $attivita->user->name ?? '-' }}</dd>
        </div>
        @if($attivita->cliente)
        <div>
            <dt class="text-xs font-medium text-gray-500 uppercase">Cliente</dt>
            <dd class="mt-1">
                <a href="{{ route('clienti.show', $attivita->cliente_id) }}" class="text-sm text-blue-600 hover:underline">
                    {{ $attivita->cliente->nome }}
                </a>
            </dd>
        </div>
        @endif
        @if($attivita->opportunita)
        <div>
            <dt class="text-xs font-medium text-gray-500 uppercase">Opportunità</dt>
            <dd class="mt-1">
                <a href="{{ route('opportunita.show', $attivita->opportunita_id) }}" class="text-sm text-blue-600 hover:underline">
                    {{ $attivita->opportunita->titolo }}
                </a>
            </dd>
        </div>
        @endif
        @if($attivita->note)
        <div>
            <dt class="text-xs font-medium text-gray-500 uppercase">Note</dt>
            <dd class="text-sm text-gray-700 mt-1">{{ $attivita->note }}</dd>
        </div>
        @endif
    </dl>

    <div class="flex gap-3 mt-6 pt-5 border-t border-gray-100">
        <a href="{{ route('attivita.edit', $attivita->id) }}"
           class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            Modifica
        </a>
        <form method="POST" action="{{ route('attivita.destroy', $attivita->id) }}"
              onsubmit="return confirm('Eliminare questa attività?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="px-5 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-sm font-medium rounded-lg transition-colors border border-red-200">
                Elimina
            </button>
        </form>
    </div>
</div>
@endsection
