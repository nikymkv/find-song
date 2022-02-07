@extends('layouts.moderator')

@section('content')
    <h2 class="text-center mt-3 mb-4" id="chart">Статистика по категориям</h2>

    <div class="row justify-content-center">
        <div class="col-10">
            <div class="row justify-content-center">
                <div class="col-5">
                    <form action="{{ route('admin.statistics') }}" method="get">
                        <select class="form-select" name="genres[]" multiple>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}" {{ in_array($genre->id, Request::get('genres', [])) ? 'selected' : '' }}>{{ $genre->name }}</option>
                            @endforeach
                        </select>
                        <input class="btn btn-primary mt-2" type="submit" value="Принять">
                    </form>
                </div>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Категория</th>
                        <th scope="col">Количество песен</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($selectGenres as $key => $genre)
                        <tr>
                            <th class="align-middle" scope="row">{{ ++$key }}</th>
                            <td class="align-middle">{{ $genre->name }}</td>
                            <td class="align-middle">{{ $genre->songs_count }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="align-middle"></th>
                        <th class="align-middle">Всего</th>
                        <th class="align-middle">{{ $selectGenres->sum('songs_count') }}</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
