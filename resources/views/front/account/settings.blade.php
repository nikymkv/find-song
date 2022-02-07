@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="text-center">Настройки профиля</h3>
        <div class="row mt-4">
            <div class="col-3">
                <img src="{{ asset('storage/' . $user->preview_image) }}" class="img-thumbnail" id="image_profile" alt="...">
                <div class="col mt-3">
                    <form action="#" action="post">
                        @csrf
                        @method('put')
                        <div class="mb-5">
                            <input class="form-control" type="file" id="formFile" onchange="uploadImage()">
                        </div>
                    </form>
                </div>
                <script>
                    function uploadImage() {
                        let file = event.target.files[0];

                        let formData = new FormData();
                        formData.append('preview_image', file);

                        axios.post("{{ route('account.settings.preview_image.update') }}", formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            }).then(res => changeImage(res.data.url))
                            .catch(err => console.log(err));
                    }

                    function changeImage(url) {
                        console.log(url);
                        image_profile.src = url;
                    }

                    function clearImage() {
                        document.getElementById('image_profile').value = null;
                        frame.src = "";
                    }
                </script>
            </div>
            <div class="col-6">
                <div class="row justify-content-center">
                    <div class="col-11">
                        <form action="{{ route('account.settings.update') }}" method="post">
                            @csrf
                            @method('put')
                            <div class="mb-3 row">
                                <label for="name" class="col-sm-2 col-form-label">Имя</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="Введите имя" value="{{ old('name', $user->name) }}">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="email" class="col-sm-2 col-form-label">Почта</label>
                                <div class="col-sm-10">
                                    <input type="text" name="email" class="form-control" id="email"
                                        placeholder="Введите почту" value="{{ old('email', $user->email) }}">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="username" class="col-sm-2 col-form-label">Юзернейм</label>
                                <div class="col-sm-10">
                                    <input type="text" name="username" class="form-control" placeholder="Введите юзернейм"
                                        value="{{ old('username', $user->username) }}">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="about" class="col-sm-2 col-form-label">О себе</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="about" id="" cols="30" rows="10">{{ old('about', $user->about) }}</textarea>
                                </div>
                            </div>
                            <div class="mb-3 text-center">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </form>

                        <h3 class="text-center mt-5 mb-4">Смена пароля</h3>

                        <form action="{{ route('account.settings.password.update') }}" method="post">
                            @csrf
                            @method('put')
                            <div class="mb-3 row">
                                <label for="password" class="col-sm-2 col-form-label">Пароль</label>
                                <div class="col-sm-10">
                                    <input type="password" name="password" class="form-control" id="password"
                                        placeholder="Введите пароль">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="password_confirmation" class="col-sm-2 col-form-label">Подтверждение
                                    пароля</label>
                                <div class="col-sm-10">
                                    <input type="password" name="password_confirmation" class="form-control"
                                        id="password_confirmation" placeholder="Введите пароль повторно">
                                </div>
                            </div>
                            <div class="mb-3 text-center">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-3">

            </div>
        </div>
    </div>
@endsection
