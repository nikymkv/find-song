@extends('layouts.app')

@section('content')
    <div class="container">

        <h2 class="text-center mb-4" id="latest">Избранные композиции</h2>

        <div class="row row-cols-1 row-cols-md-3 g-5">
            @foreach ($favouritesSongs as $song)
                <div class="col">
                    <a href="{{ route('songs.view', ['song' => $song]) }}">
                        <div class="card">
                            <img src="{{ asset('storage/' . $song->preview_image) }}" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">{{ $song->name }}</h5>
                                <p class="card-text"><a
                                        href="{{ route('authors.view', ['user' => $song->author]) }}">Автор:
                                        {{ $song->author->name }}</a></p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="row mt-3 justify-content-center">
            <div class="col-3">
                {{ $favouritesSongs->links() }}
            </div>
        </div>
    </div>
@endsection
