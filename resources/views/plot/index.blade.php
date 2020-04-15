@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                Получение кадастровых данных
            </div>
            <form action="check" method="POST">
                @csrf
                <div class="form-group text-left">
                    <input type="text" id="cadastralNumber" name="cadastralNumber" value="{{$lastInput}}"
                           placeholder="Введите кадастровые номера" class="form-control my-1" required>
                    <small id="cadastralNumberHelp" class="form-text text-muted">Введите кадастровые номера через
                        запятую. Например, "69:27:0000022:1306, 69:27:0000022:1307"</small>
                    <button type="submit" name="submit" value="submit" class="btn btn-primary my-1">Проверить</button>
                    @foreach($errors->all() as $message)
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @endforeach
                    @if(session()->has('server-error'))
                        <div class="text-danger">
                            {{ session('server-error') }}
                        </div>
                    @endif
                </div>
            </form>
            @if($searchResult)
                <table class="table table-sm table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">Кадастровый номер</th>
                        <th scope="col">Адрес</th>
                        <th scope="col" style="width: 13%">Стоимость</th>
                        <th scope="col" style="width: 13%">Площадь</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($searchResult as $row)
                        <tr>
                            <th>{{$row->cadastral_number}}</th>
                            <td>{{$row->address}}</td>
                            <td>{{$row->formatPrice}} ₽</td>
                            <td>{{$row->formatArea}} м<sup>2</sup></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
