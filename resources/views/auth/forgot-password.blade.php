@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                    Mot de passe oublié
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                    Entrez votre adresse email pour recevoir un lien de réinitialisation
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-8">
                @if (session('status'))
                    <div
                        class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded dark:bg-green-900 dark:border-green-700 dark:text-green-300">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Adresse email
                        </label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" required
                                class="input @error('email') border-red-500 @enderror" placeholder="votre@email.com"
                                value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="btn-primary w-full">
                            Envoyer le lien de réinitialisation
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}"
                        class="text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                        ← Retour à la connexion
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
