@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-3">
                <img src="{{ asset('storage/' . $user->preview_image) }}" class="img-thumbnail" alt="...">

                <div class="col-10 mt-3">
                    <h3 class="text-center">{{ $user->name }}</h3>

                    {!! $user->about ?? '<p>Описание отсутствует</p>' !!}
                </div>

                <div class="col-10 mt-5">
                    <h5 class="text-center">Статистика</h5>
                    <p class="text-left mt-2">Количество композиций: {{ $user->songs()->count() }}</p>
                    <p class="text-left">Рейтинг: {{ $user->avgRate() }}</p>
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
                                            {{ $rateByUser == 5 ? 'checked' : '' }}>
                                        <label class="star-rating__ico fa fa-star-o fa-lg" for="star-5" title="Отлично"></label>
                                        <input class="star-rating__input" id="star-4" type="radio" name="rating" value="4"
                                            {{ $rateByUser == 4 ? 'checked' : '' }}>
                                        <label class="star-rating__ico fa fa-star-o fa-lg" for="star-4" title="Хорошо"></label>
                                        <input class="star-rating__input" id="star-3" type="radio" name="rating" value="3"
                                            {{ $rateByUser == 3 ? 'checked' : '' }}>
                                        <label class="star-rating__ico fa fa-star-o fa-lg" for="star-3"
                                            title="Удовлетворительно"></label>
                                        <input class="star-rating__input" id="star-2" type="radio" name="rating" value="2"
                                            {{ $rateByUser == 2 ? 'checked' : '' }}>
                                        <label class="star-rating__ico fa fa-star-o fa-lg" for="star-2" title="Плохо"></label>
                                        <input class="star-rating__input" id="star-1" type="radio" name="rating" value="1"
                                            {{ $rateByUser == 1 ? 'checked' : '' }}>
                                        <label class="star-rating__ico fa fa-star-o fa-lg" for="star-1" title="Ужасно"></label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endauth

                </div>
            </div>

            @auth
                <script>
                    let radioArray = document.getElementsByName('rating');

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

                    function rateRequest(e) {
                        let rate = parseInt(e.target.value)
                        if (!Number.isInteger(rate)) {
                            return;
                        }

                        sendPostData("{{ route('authors.rate', ['author' => $user]) }}", {
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

            <div class="col-8">
                <h3 class="text-center">Композиции</h3>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::get('type', 'popular') == 'popular' ? 'active' : '' }}"
                            aria-current="page" href="{{ route('authors.view', ['user' => $user]) }}">Популярное</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::get('type') == 'new' ? 'active' : '' }}"
                            href="{{ route('authors.view', ['user' => $user]) . '?type=new' }}">Новые</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::get('type') == 'all' ? 'active' : '' }}"
                            href="{{ route('authors.view', ['user' => $user]) . '?type=all' }}">Все</a>
                    </li>
                </ul>
                <div class="row justify-content-center">
                    <div class="col">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Обложка</th>
                                    <th scope="col">Название</th>
                                    <th scope="col">Исполнитель</th>
                                    <th scope="col">Рейтинг</th>
                                    <th scope="col">Просмотров</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($songs as $key => $song)
                                    <tr>
                                        <td class="td-image">
                                            <img src="{{ asset('storage/' . $song->preview_image) }}"
                                                class="img-thumbnail">
                                        </td>
                                        <td class="align-middle"><a
                                                href="{{ route('songs.view', ['song' => $song]) }}">{{ $song->name }}</a>
                                        </td>
                                        <td class="align-middle">{{ $song->author->name }}</td>
                                        <td class="align-middle">{{ $song->avgRate() }}</td>
                                        <td class="align-middle">{{ $song->views_count }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        {{ $songs->links() }}
                    </div>
                </div>
            </div>
            <div class="col-3">

            </div>
        </div>
    </div>
@endsection
