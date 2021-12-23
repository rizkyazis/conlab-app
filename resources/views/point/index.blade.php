@extends('layouts.app')

@push('css')
    <style>
        html {
            height: 100%;
        }

        .jumbotron {
            height: 100%;
            background: rgb(0, 123, 255);
            background: linear-gradient(180deg, rgba(0, 123, 255, 1) 57%, rgba(151, 202, 219, 1) 100%);
        }

        .card-img-top {
            width: 100%;
            height: 10vw;
            object-fit: cover;
        }

        .card-body {
            width: 100%;
            height: 7vw;
        }

        .card {
            box-shadow: 0 0 0 grey;
            -webkit-transition: box-shadow .15s ease-out;
        }

        .card:hover {
            box-shadow: 1px 8px 20px grey;
            -webkit-transition: box-shadow .15s ease-in;
        }

        a.custom-card,
        a.custom-card:hover {
            color: inherit;
            text-decoration: none;
        }

        .custom-select {
            outline: 0;
        }
    </style>
@endpush
@section('content')
    <table>
        <tr>
            <th>Ranking</th>
            <th>Name</th>
            <th>Point</th>
        </tr>
        @foreach($rankings as $ranking)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$ranking['name']}}</td>
                <td>{{$ranking['point']}}</td>
            </tr>
        @endforeach
    </table>
@endsection

@push('script')
@endpush
