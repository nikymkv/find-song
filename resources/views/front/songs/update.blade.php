@extends('layouts.app')

@section('content')
    @if ($errors->any())
        {!! implode('', $errors->all('<div>:message</div>')) !!}
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-6">
                <h3 class="text-center mb-4">Редактирование песни</h3>
                <form action="{{ route('songs.update', ['song' => $song]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="mb-3 row">
                        <label for="name" class="col-sm-3 col-form-label">Название</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Введите имя" value="{{ old('name', $song->name) }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="featuring_with" class="col-sm-3 col-form-label">Совместно с</label>
                        <div class="col-sm-9">
                            <input type="text" name="featuring_with" class="form-control" id="featuring_with"
                                placeholder="Введите соисполнителя" value="{{ old('featuring_with', $song->featuring_with) }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="producer" class="col-sm-3 col-form-label">Продюсер</label>
                        <div class="col-sm-9">
                            <input type="text" name="producer" id="producer" class="form-control"
                                placeholder="Введите продюсера" value="{{ old('producer', $song->producer) }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="text_written_by" class="col-sm-3 col-form-label">Автор текста</label>
                        <div class="col-sm-9">
                            <input type="text" name="text_written_by" id="text_written_by" class="form-control"
                                placeholder="Введите автора" value="{{ old('text_written_by', $song->text_written_by) }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="music_written_by" class="col-sm-3 col-form-label">Автор бита</label>
                        <div class="col-sm-9">
                            <input type="text" name="music_written_by" id="music_written_by" class="form-control"
                                placeholder="Введите автора бита" value="{{ old('music_written_by', $song->music_written_by) }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="mixed_by" class="col-sm-3 col-form-label">Свел</label>
                        <div class="col-sm-9">
                            <input type="text" name="mixed_by" id="mixed_by" class="form-control"
                                placeholder="Введите данные" value="{{ old('mixed_by', $song->mixed_by) }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="genres" class="col-sm-3 col-form-label">Жанр</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="genre_id" id="genres">
                                <option value="0" selected disabled>Выберите жанр</option>
                                @foreach ($genres as $genre)
                                    <option value="{{ $genre->id }}" {{ old('genre_id', $song->genre_id) == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="text" class="col-sm-3 col-form-label">Текст</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="text" id="text" rows=20>{{ old('text', $song->text) }}</textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="preview_image" class="col-sm-3 col-form-label">Обложка</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="file" name="preview_image" id="preview_image"
                                onchange="setPreview()">
                            <img src="{{ asset('storage/' . $song->preview_image) }}" class="img-thumbnail mt-4" id="previewImg">
                        </div>
                        <script>
                            function setPreview() {
                                previewImg.src = URL.createObjectURL(event.target.files[0]);
                                if (previewImg.hidden == true) {
                                    previewImg.hidden = false;
                                }
                            }
                        </script>
                    </div>
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
