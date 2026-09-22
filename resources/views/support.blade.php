@extends('layouts.app')

@section('title', 'Техническая поддержка — Asu Oil Trade')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="bg-white rounded-lg shadow-md p-6 sm:p-10">

            <header class="border-b border-gray-200 pb-6 mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                    Техническая поддержка
                </h1>
                <p class="mt-2 text-gray-600">
                    Опишите вопрос и оставьте номер телефона — мы свяжемся с вами.
                </p>
            </header>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('support.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-900 mb-1">
                        Номер телефона
                    </label>
                    <input id="phone" name="phone" type="tel" required
                           inputmode="tel" autocomplete="tel" maxlength="20"
                           value="{{ old('phone') }}"
                           placeholder="+7 (777) 111-22-33"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="question" class="block text-sm font-medium text-gray-900 mb-1">
                        Вопрос
                    </label>
                    <textarea id="question" name="question" rows="6" required maxlength="5000"
                              placeholder="Опишите, что случилось"
                              class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('question') border-red-500 @enderror">{{ old('question') }}</textarea>
                    @error('question')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full sm:w-auto inline-flex justify-center py-2.5 px-6 rounded-md text-sm font-medium text-gray-900 bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    Отправить
                </button>
            </form>

        </div>
    </div>
@endsection
