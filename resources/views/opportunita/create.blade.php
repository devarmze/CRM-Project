@extends('layouts.app')

@section('title', 'Nuova Opportunità')
@section('page-title', 'Nuova Opportunità')

@section('content')
<div class="max-w-2xl">
    <div class="mb-4">
        <a href="{{ route('opportunita.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Torna alle opportunità
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Crea nuova opportunità</h2>

        @if($errors->any())
        <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg">
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('opportunita.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titolo <span class="text-red-500">*</span></label>
                <input type="text" name="titolo" value="{{ old('titolo') }}" required
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente <span class="text-red-500">*</span></label>
                    <select name="cliente_id" required
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                        <option value="">Seleziona cliente</option>
                        @foreach($clienti as $c)
                        <option value="{{ $c->id }}" {{ old('cliente_id', $cliente_id) == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fase <span class="text-red-500">*</span></label>
                    <select name="fase" required
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                        <option value="nuovo" {{ old('fase', 'nuovo') === 'nuovo' ? 'selected' : '' }}>Nuovo</option>
                        <option value="in_trattativa" {{ old('fase') === 'in_trattativa' ? 'selected' : '' }}>In trattativa</option>
                        <option value="vinto" {{ old('fase') === 'vinto' ? 'selected' : '' }}>Vinto</option>
                        <option value="perso" {{ old('fase') === 'perso' ? 'selected' : '' }}>Perso</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valore stimato (€) <span class="text-red-500">*</span></label>
                    <input type="number" name="valore_stimato" value="{{ old('valore_stimato', 0) }}" min="0" step="0.01" required
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data chiusura prevista</label>
                    <input type="date" name="data_chiusura_prevista" value="{{ old('data_chiusura_prevista') }}"
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
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
                    Crea opportunità
                </button>
                <a href="{{ route('opportunita.index') }}"
                   class="px-6 py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    Annulla
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
