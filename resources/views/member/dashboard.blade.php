@extends('layouts.app') {{-- Or use your layout file name --}}

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
        @foreach ($stats as $card)
            {!! $card->render() !!}
        @endforeach
    </div>
@endsection
