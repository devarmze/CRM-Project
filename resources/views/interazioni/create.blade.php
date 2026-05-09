@extends('layouts.app')

@section('title', 'Nuova Interazione')
@section('page-title', 'Nuova Interazione')

@section('content')
<div class="max-w-lg">
    <div class="mb-4">
        <a href="{{ route('clienti.show', $cliente->id) }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Torna a {{ $cliente->nome }}
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Registra interazione con <span class="text-blue-600">{{ $cliente->nome }}</span></h2>

        @if($errors->any())
        <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg">
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('clienti.interazioni.store', $cliente->id) }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data e ora <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="data" value="{{ old('data', now()->format('Y-m-d\TH:i')) }}" required
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                <input type="text" name="tipo" value="{{ old('tipo') }}" required
                       placeholder="es. Telefonata, Visita, Email..."
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrizione <span class="text-red-500">*</span></label>
                <textarea name="descrizione" rows="4" required
                          class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">{{ old('descrizione') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Esito</label>
                <input type="text" name="esito" value="{{ old('esito') }}"
                       placeholder="es. Positivo, Richiesta preventivo..."
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Registra interazione
                </button>
                <a href="{{ route('clienti.show', $cliente->id) }}"
                   class="px-6 py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    Annulla
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
