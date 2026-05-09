@extends('layouts.app')

@section('title', 'Modifica ' . $cliente->nome)
@section('page-title', 'Modifica Cliente')

@section('content')
<div class="max-w-2xl">
    <div class="mb-4">
        <a href="{{ route('clienti.show', $cliente->id) }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Torna al cliente
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Modifica: {{ $cliente->nome }}</h2>

        @if($errors->any())
        <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg">
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('clienti.update', $cliente->id) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $cliente->nome) }}" required
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                    <select name="tipo" required
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                        <option value="azienda" {{ old('tipo', $cliente->tipo) === 'azienda' ? 'selected' : '' }}>Azienda</option>
                        <option value="privato" {{ old('tipo', $cliente->tipo) === 'privato' ? 'selected' : '' }}>Privato</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stato <span class="text-red-500">*</span></label>
                    <select name="stato" required
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                        <option value="prospect" {{ old('stato', $cliente->stato) === 'prospect' ? 'selected' : '' }}>Prospect</option>
                        <option value="attivo" {{ old('stato', $cliente->stato) === 'attivo' ? 'selected' : '' }}>Attivo</option>
                        <option value="inattivo" {{ old('stato', $cliente->stato) === 'inattivo' ? 'selected' : '' }}>Inattivo</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $cliente->email) }}"
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}"
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Città</label>
                    <input type="text" name="citta" value="{{ old('citta', $cliente->citta) }}"
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Settore</label>
                    <input type="text" name="settore" value="{{ old('settore', $cliente->settore) }}"
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Salva modifiche
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
