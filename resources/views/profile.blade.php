@extends('layouts.app')

@push('css')
    <style>
        html {
            height: 100%;
        }

        .jumbotron {
            height: 100%;
            background: #4D80E4;
            border-radius: 0px 0px 20px 20px;
        }

        .btn-blue {
            background: #46B3E6;
        }

        .card-img-top {
            width: 100%;
            height: 10vw;
            object-fit: fill;
            padding: 30px;
        }

        .card {
            box-shadow: 0 0 0 grey;
            -webkit-transition: box-shadow .15s ease-out;
            background: #DFF6F0;
        }

        .card:hover {
            box-shadow: 1px 8px 20px grey;
            -webkit-transition: box-shadow .15s ease-in;
        }
    </style>
@endpush
@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col mt-5">
                <p class="h1 font-weight-bold">Profile</p>
                <img style="width: 80%; height: 80%" src="{{'images/profile-people.svg'}}">
            </div>
            <div class="col-8">
                <img style="width: 90%; height: 90%" src="{{ asset('images/people-laptop.svg') }}">
            </div>
        </div>
    </div>
@endsection
