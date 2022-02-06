<x-slot name="header">
    <div class="flex justify-between items-center h-10">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
        <div class="flex items-center py-3 justify-end text-right">
            <x-jet-button>
                <a href="{{ route('posts') }}">{{ __('Posts') }}</a>
            </x-jet-button>
        </div>
    </div>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="container pb-4 w-full">
                    <img class="mx-auto" src="{{ asset('images/' . $image) }}" alt="{{ $title }}">
                </div>
                <h1 class="text-2xl font-extrabold pb-4 text-center">{{ $title }}</h1>
                <p class="text-center">{!! $body !!}</p>
            </div>
        </div>
    </div>
</div>
