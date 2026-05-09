@extends('layouts.app')

@section('title', 'Attività')
@section('page-title', 'Attività')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $attivita->total() }} attività trovate</p>
    <a href="{{ route('attivita.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuova attività
    </a>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-5 shadow-sm">
    <form method="GET" action="{{ route('attivita.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="w-44">
            <label class="block text-xs font-medium text-gray-500 mb-1">Stato</label>
            <select name="stato" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                <option value="">Tutti gli stati</option>
                <option value="da_fare" {{ request('stato') === 'da_fare' ? 'selected' : '' }}>Da fare</option>
                <option value="completata" {{ request('stato') === 'completata' ? 'selected' : '' }}>Completata</option>
            </select>
        </div>
        <div class="w-44">
            <label class="block text-xs font-medium text-gray-500 mb-1">Tipo</label>
            <select name="tipo" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                <option value="">Tutti i tipi</option>
                <option value="chiamata" {{ request('tipo') === 'chiamata' ? 'selected' : '' }}>Chiamata</option>
                <option value="email" {{ request('tipo') === 'email' ? 'selected' : '' }}>Email</option>
                <option value="incontro" {{ request('tipo') === 'incontro' ? 'selected' : '' }}>Incontro</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                Filtra
            </button>
            @if(request()->hasAny(['stato','tipo']))
            <a href="{{ route('attivita.index') }}" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
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
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stato</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Opportunità</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Agente</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Data</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Azioni</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($attivita as $att)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5 capitalize font-medium text-gray-900">{{ $att->tipo }}</td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            {{ $att->stato === 'completata' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $att->stato === 'completata' ? 'Completata' : 'Da fare' }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-gray-600">
                        @if($att->cliente)
                        <a href="{{ route('clienti.show', $att->cliente_id) }}" class="hover:text-blue-600">{{ $att->cliente->nome }}</a>
                        @else
                        <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-gray-600">
                        @if($att->opportunita)
                        <a href="{{ route('opportunita.show', $att->opportunita_id) }}" class="hover:text-blue-600 text-xs">{{ $att->opportunita->titolo }}</a>
                        @else
                        <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $att->user->name ?? '-' }}</td>
                    <td class="px-5 py-3.5 text-gray-500">{{ $att->data->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('attivita.edit', $att->id) }}"
                               class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                Modifica
                            </a>
                            <form method="POST" action="{{ route('attivita.destroy', $att->id) }}"
                                  onsubmit="return confirm('Eliminare questa attività?')">
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
                        Nessuna attività trovata.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($attivita->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $attivita->links() }}
    </div>
    @endif
</div>
@endsection
