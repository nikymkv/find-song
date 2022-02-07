@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-3">
                <img src="{{ asset('storage/' . $song->preview_image) }}" class="img-thumbnail" alt="preview">

                <div class="col-10 mt-5">
                    <h5 class="text-center">Статистика</h5>
                    <p class="text-left mt-2">Количество просмотров: {{ $song->views()->count() }}</p>
                    <p class="text-left">Рейтинг: {{ $song->avgRate() }}</p>
                    @guest
                        <p class="text-left">
                            <a href="{{ route('login') }}">Авторизуйтесь для оценивания</a>
                        </p>
                    @endguest
                    @auth
                        <form>
                            <div class="form-group mt-2">
                                <label>Ваша оценка</label>
                                <div class="star-rating">
                                    <div class="star-rating__wrap">
                                        <input class="star-rating__input" id="star-5" type="radio" name="rating" value="5"
                                            {{ $userRate == 5 ? 'checked' : '' }}>
                                        <label class="star-rating__ico fa fa-star-o fa-lg" for="star-5" title="Отлично"></label>

                                        <input class="star-rating__input" id="star-4" type="radio" name="rating" value="4"
                                            {{ $userRate == 4 ? 'checked' : '' }}>
                                        <label class="star-rating__ico fa fa-star-o fa-lg" for="star-4" title="Хорошо"></label>

                                        <input class="star-rating__input" id="star-3" type="radio" name="rating" value="3"
                                            {{ $userRate == 3 ? 'checked' : '' }}>
                                        <label class="star-rating__ico fa fa-star-o fa-lg" for="star-3"
                                            title="Удовлетворительно"></label>

                                        <input class="star-rating__input" id="star-2" type="radio" name="rating" value="2"
                                            {{ $userRate == 2 ? 'checked' : '' }}>
                                        <label class="star-rating__ico fa fa-star-o fa-lg" for="star-2" title="Плохо"></label>

                                        <input class="star-rating__input" id="star-1" type="radio" name="rating" value="1"
                                            {{ $userRate == 1 ? 'checked' : '' }}>
                                        <label class="star-rating__ico fa fa-star-o fa-lg" for="star-1" title="Ужасно"></label>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="form-group mt-2">
                            <button class="btn btn-primary" id="favourite_btn">{{ $isFavouriteSong ? 'В избранном' : 'В избранное' }}</button>
                        </div>
                    @endauth
                </div>

                @auth
                    <script>
                        let radioArray = document.getElementsByName('rating');
                        let favouriteBtn = document.getElementById('favourite_btn');
                        favouriteBtn.addEventListener('click', favouriteRequest);

                        for (const radioInput of radioArray) {
                            radioInput.addEventListener('click', rateRequest)
                        }

                        async function sendPostData(url = '', data = {}) {
                            let param = 'csrf-token';
                            let token = document.querySelector('meta[name=csrf-token]').getAttribute('content')

                            data[param] = token;

                            const response = await fetch(url, {
                                method: 'POST',
                                mode: 'cors',
                                cache: 'no-cache',
                                credentials: 'same-origin',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-Token': token
                                },
                                redirect: 'follow',
                                referrerPolicy: 'no-referrer',
                                body: JSON.stringify(data)
                            });

                            return await response.json();
                        }

                        function favouriteRequest(e) {
                            sendPostData("{{ route('songs.favourite', ['song' => $song]) }}", {
                                    '_method': 'put'
                                })
                                .then((data) => {
                                    if (data.status) {
                                        e.target.innerText = 'В избранном';
                                    } else {
                                        e.target.innerText = 'В избранное';
                                    }
                                })
                                .catch((err) => {
                                    console.log(err);
                                });
                        }

                        function rateRequest(e) {
                            let rate = parseInt(e.target.value)
                            if (!Number.isInteger(rate)) {
                                return;
                            }

                            sendPostData("{{ route('songs.rate', ['song' => $song]) }}", {
                                    'rate': e.target.value,
                                    '_method': 'put'
                                })
                                .then((data) => {
                                    console.log('success')
                                })
                                .catch((err) => {
                                    console.log(err)
                                });
                        }
                    </script>
                @endauth

                <div class="col-10 mt-5">
                    <h5 class="text-center">Информация</h5>
                    <p class="text-left">Жанр: {{ $song->genre->name }}</p>
                    <p class="text-left">Автор текста: {{ $song->text_written_by }}</p>
                    <p class="text-left">Продюсер: {{ $song->producer }}</p>
                    <p class="text-left">Автор бита: {{ $song->music_written_by }}</p>
                    <p class="text-left">Свел: {{ $song->mixed_by }}</p>
                </div>
            </div>
            <div class="col-6">
                <h3 class="text-center">{{ $song->author->name }}
                    {{ $song->featuring_with ? '(feat) ' . $song->featuring_with : '' }} - {{ $song->name }}</h3>

                <div class="row justify-content-center">
                    <div class="col-9">
                        {!! $song->text !!}
                    </div>
                </div>
            </div>
            <div class="col">
                <h5 class="text-center mb-3">Еще композиции</h5>

                @foreach ($suggestedSongs as $song)
                    <p class="text-left">
                        <a href="{{ route('songs.view', ['song' => $song]) }}">{{ $song->author->name }}
                            {{ $song->featuring_with ? ' (feat) ' . $song->featuring_with : '' }} -
                            {{ $song->name }}</a>
                    </p>
                @endforeach
            </div>
        </div>
    </div>
@endsection
