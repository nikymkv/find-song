@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <form class="d-flex">
                    <div class="col-10">
                        <select name="search" id="select_search">
                            <option disabled>Введите ваш запрос</option>
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select" id="search_genre">
                            <option disabled>Жанр</option>
                            <option value="1">Все</option>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}"
                                    {{ Request::get('genre', 0) == $genre->id ? 'selected' : '' }}>{{ $genre->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <h2 class="text-center mb-4" id="latest">Новые песни</h2>

        <div class="row row-cols-1 row-cols-md-3 g-5">
            @foreach ($newSongs as $song)
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

        <h2 class="text-center mt-5 mb-4" id="newAuthors">Новые исполнители</h2>

        <div class="row row-cols-1 row-cols-md-3 g-5">
            @foreach ($newAuthors as $author)
                <div class="col">
                    <a href="{{ route('authors.view', ['user' => $author]) }}">
                        <div class="card">
                            <img src="{{ asset('storage/' . $author->preview_image) }}"
                                class="card-img-top img-fluid img-responsive">
                            <div class="card-body">
                                <h5 class="card-title">{{ $author->name }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <h2 class="text-center mt-5 mb-4" id="chart">Чарт</h2>

        <div class="row justify-content-center">
            <div class="col-5">
                <form action="{{ route('home') . '#chart' }}" method="get">
                    <div class="row">
                        <select class="col form-select" name="genre">
                            <option disabled>Жанр</option>
                            <option value="0">Все</option>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}"
                                    {{ Request::get('genre', 0) == $genre->id ? 'selected' : '' }}>{{ $genre->name }}
                                </option>
                            @endforeach
                        </select>
                        <select class="col form-select" name="period">
                            <option disabled>Период</option>
                            <option value="day" {{ Request::get('period', 'day') == 'day' ? 'selected' : '' }}>Сегодня
                            </option>
                            <option value="week" {{ Request::get('period') == 'week' ? 'selected' : '' }}>Неделя</option>
                            <option value="month" {{ Request::get('period') == 'month' ? 'selected' : '' }}>Месяц
                            </option>
                        </select>
                        <input class="col btn btn-primary" type="submit" value="Принять">
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function(event) {
                $('#select_search').select2({
                    width: '100%',
                    ajax: {
                        url: "{{ route('songs.search') }}",
                        contentType: 'application/json',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            let genre = $('#search_genre').val()
                            return {
                                query: params.term,
                                genre: genre
                            };
                        },
                        processResults: function(data, params) {
                            return {
                                results: $.map(data, function(item) {
                                    let feat = item.featuring_with ? ' ' + item.featuring_with : '';
                                    return {
                                        text: item.author.name + ' (feat)' + feat + ' - ' + item
                                            .name,
                                        id: item.id,
                                        author: item.author.name
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                    placeholder: 'Поиск песни по фразе',
                    minimumInputLength: 3,
                });

                $('#select_search').on('select2:selecting', function(e) {
                    let url = "{{ route('songs.view', ['song' => ':song']) }}".replace(':song', e.params.args
                        .data.id);
                    document.location.href = url;
                });
            });
        </script>

        <div class="row">
            <div class="col">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Обложка</th>
                            <th scope="col">Название</th>
                            <th scope="col">Исполнитель</th>
                            <th scope="col">Рейтинг</th>
                            <th scope="col">Просмотров</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($songsChart as $song)
                            <tr>
                                <th class="align-middle" scope="row">1</th>
                                <td class="td-image">
                                    <img src="{{ asset('storage/' . $song->preview_image) }}" class="img-thumbnail"
                                        alt="...">
                                </td>
                                <td class="align-middle">{{ $song->name }}</td>
                                <td class="align-middle">{{ $song->author->name }}</td>
                                <td class="align-middle">{{ $song->rate }}</td>
                                <td class="align-middle">{{ $song->views()->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
