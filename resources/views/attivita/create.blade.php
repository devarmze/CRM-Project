@extends('layouts.app')

@section('title', 'Nuova Attività')
@section('page-title', 'Nuova Attività')

@section('content')
<div class="max-w-2xl">
    <div class="mb-4">
        <a href="{{ route('attivita.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Torna alle attività
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Crea nuova attività</h2>

        @if($errors->any())
        <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg">
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('attivita.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                    <select name="tipo" required
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                        <option value="chiamata" {{ old('tipo', 'chiamata') === 'chiamata' ? 'selected' : '' }}>Chiamata</option>
                        <option value="email" {{ old('tipo') === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="incontro" {{ old('tipo') === 'incontro' ? 'selected' : '' }}>Incontro</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stato <span class="text-red-500">*</span></label>
                    <select name="stato" required
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                        <option value="da_fare" {{ old('stato', 'da_fare') === 'da_fare' ? 'selected' : '' }}>Da fare</option>
                        <option value="completata" {{ old('stato') === 'completata' ? 'selected' : '' }}>Completata</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data e ora <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="data" value="{{ old('data', now()->format('Y-m-d\TH:i')) }}" required
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                    <select name="cliente_id"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                        <option value="">Nessuno</option>
                        @foreach($clienti as $c)
                        <option value="{{ $c->id }}" {{ old('cliente_id', $cliente_id) == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Opportunità collegata</label>
                    <select name="opportunita_id"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                        <option value="">Nessuna</option>
                        @foreach($opportunita as $opp)
                        <option value="{{ $opp->id }}" {{ old('opportunita_id', $opportunita_id) == $opp->id ? 'selected' : '' }}>
                            {{ $opp->titolo }} ({{ $opp->cliente->nome ?? '-' }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                <textarea name="note" rows="3"
                          class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">{{ old('note') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Crea attività
                </button>
                <a href="{{ route('attivita.index') }}"
                   class="px-6 py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    Annulla
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
