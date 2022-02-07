@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h3 class="text-center mb-4">Личный кабинет</h3>
            <div class="col-3"></div>
            <div class="col">
                <h4 class="text-center">Статистика по аккаунту</h4>
                <div class="row">
                    <div class="col">
                        <h5 class="text-center">Композиций</h5>
                        <p class="text-center">{{ $accountStatistics->get('total_songs', 'Нет данных') }}</p>
                    </div>
                    <div class="col">
                        <h5 class="text-center">Рейтинг</h5>
                        <p class="text-center">{{ $accountStatistics->get('avg_rate', 'Нет данных') }}</p>
                    </div>
                    <div class="col">
                        <h5 class="text-center">Просмотров</h5>
                        <p class="text-center">{{ $accountStatistics->get('total_views', 'Нет данных') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-3"></div>
        </div>
        <div class="row justify-content-center">
            <div class="col">
                <div class="row row-cols-1 row-cols-md-3 g-5">
                    @foreach ($songs as $song)
                        <div class="col">
                            <a href="{{ route('songs.view', ['song' => $song]) }}">
                                <div class="card">
                                    <img src="{{ asset('storage/' . $song->preview_image) }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $song->name }}</h5>
                                        <p class="card-text">Автор: {{ $song->author->name }}</p>
                                        <a class="text-right" href="{{ route('songs.edit', ['song' => $song]) }}">(ред)</a>
                                        <a class="text-right" href="{{ route('songs.edit', ['song' => $song]) }}">(Удалить)</a>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-3">
                {{ $songs->links() }}
            </div>
        </div>
    </div>
@endsection
