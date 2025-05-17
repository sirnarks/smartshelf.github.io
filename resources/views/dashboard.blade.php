@extends('layouts.app')

@section('content')
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($stats as $card)
                <div class="bg-white shadow rounded p-6">
                    <h2 class="text-sm text-gray-500">{{ $card->getHeading() }}</h2>
                    <div class="text-2xl font-bold mt-2">
                        {{ $card->getValue() }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
