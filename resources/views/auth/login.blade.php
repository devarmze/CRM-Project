<!DOCTYPE html>
<html lang="it" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accedi — CRM Gestionale</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center">

    <div class="w-full max-w-md">
        {{-- Logo card --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl mb-4 shadow-lg">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">CRM Gestionale</h1>
            <p class="mt-2 text-gray-500 text-sm">Accedi al tuo account</p>
        </div>

        {{-- Login form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Indirizzo email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-4 py-2.5 border rounded-lg text-sm transition-colors
                               {{ $errors->has('email') ? 'border-red-400 bg-red-50 focus:ring-red-500' : 'border-gray-300 focus:border-blue-500' }}
                               focus:outline-none focus:ring-2 focus:ring-offset-0"
                        placeholder="email@esempio.it"
                    >
                    @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-2.5 border rounded-lg text-sm transition-colors
                               {{ $errors->has('password') ? 'border-red-400 bg-red-50 focus:ring-red-500' : 'border-gray-300 focus:border-blue-500' }}
                               focus:outline-none focus:ring-2 focus:ring-offset-0"
                        placeholder="••••••••"
                    >
                    @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Ricordami</label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm transition-colors shadow-sm"
                >
                    Accedi
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-gray-400">
            CRM Gestionale &mdash; Sistema di gestione clienti
        </p>
    </div>

</body>
</html>
