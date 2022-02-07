@extends('layouts.moderator')

@section('content')
    <div class="row justify-content-center">
        <h3 class="text-center mb-4">Панель модератора</h3>
        <div class="col-3"></div>
        <div class="col">
            <h4 class="text-center">Статистика по композициям</h4>
            <div class="row">
                <div class="col">
                    <h5 class="text-center">Всего</h5>
                    <p class="text-center">{{ $songCount }}</p>
                </div>
                <div class="col">
                    <h5 class="text-center">Опубликованных</h5>
                    <p class="text-center">{{ $moderatedSongCount }}</p>
                </div>
                <div class="col">
                    <h5 class="text-center">Не опубликованных</h5>
                    <p class="text-center">{{ $notModeratedSongCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-3"></div>
    </div>

    <div class="row justify-content-center">
        <div class="col"></div>
        <div class="col-10">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Обложка</th>
                        <th scope="col">Название</th>
                        <th scope="col">Исполнитель</th>
                        <th scope="col">Жанр</th>
                        <th scope="col">Дата</th>
                        <th scope="col">Статус</th>
                        <th scope="col">Действие</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($songs as $song)
                        <tr>
                            <td class="td-image">
                                <img src="{{ asset('storage/' . $song->preview_image) }}" class="img-thumbnail"
                                    alt="...">
                            </td>
                            <td class="align-middle">{{ $song->name }}</td>
                            <td class="align-middle">{{ $song->author->name }}</td>
                            <td class="align-middle">{{ $song->genre->name }}</td>
                            <td class="align-middle">{{ $song->created_at }}</td>
                            <td class="align-middle" id="{{ 'td_status_' . $song->id }}">
                                {{ $song->is_moderated ? 'Опубликовано' : 'Не опубликовано' }}</td>
                            <td class="align-middle">
                                <button class="btn btn-primary" name="moderateBtn" data-song_id="{{ $song->id }}">
                                    {{ $song->is_moderated ? 'Отклонить' : 'Принять' }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row justify-content-center">
                <div class="col-4">
                    {{ $songs->links() }}
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
    <script>
        let moderateBtns = document.getElementsByName('moderateBtn');

        for (const btn of moderateBtns) {
            btn.addEventListener('click', moderateRequest);
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

        function moderateRequest(e) {
            let url = "{{ route('admin.moderate', ['song' => ':song']) }}".replace(':song', e.target.dataset.song_id)
            sendPostData(url, {
                    '_method': 'put'
                })
                .then((data) => {
                    let td = document.querySelector('#td_status_' + e.target.dataset.song_id);
                    if (data.status) {
                        td.innerHTML = 'Опубликовано';
                        e.target.innerText = 'Отклонить';
                    } else {
                        td.innerHTML = 'Не опубликовано';
                        e.target.innerText = 'Принять';
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        }
    </script>
@endsection
