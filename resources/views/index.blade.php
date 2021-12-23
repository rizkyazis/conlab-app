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
    <div class="jumbotron">
        <div class="container">
            <p class="h3 text-white font-weight-bold">Learn Programming now</p>
            <div class="my-1"></div>
            <p class="lead text-white">Join our courses to get started.</p>
            @guest
            <form action="{{route('get.started')}}" method="post" class="form-inline">
                @csrf
                <div class="form-group mr-3 mb-2">
                    <label for="email" class="sr-only">Email</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Your email">
                </div>
                <button type="submit" class="btn btn-blue mb-2 shadow text-white">Start Course</button>
            </form>
            @endguest
        </div>
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col mt-5">
                <p class="h1 font-weight-bold">Welcome!</p>
                <p>Conlab adalah aplikasi kursus dasar pemrograman berbasis website yang dirancang untuk pemula. Anda
                    bisa belajar dasar pemrograman sejak dini. Conlab menyediakan berbagai pemrograman dasar dengan
                    beragam bahasa pemrograman</p>
                <a class="btn btn-primary btn-block rounded-pill p-3 pl-5 pr-5 shadow" href="{{ route('language') }}"
                   role="button"><span
                        class="h4">See all courses</span></a>
            </div>
            <div class="col-8">
                <img style="width: 90%; height: 90%" src="{{ asset('images/people-laptop.svg') }}">
            </div>
        </div>
        <h1 class="text-center mt-5 font-weight-bold">Mengapa Harus Conlab?</h1>
        <div class="row row-cols-1 row-cols-md-3 mt-5">
            <div class="col mb-4">
                <div class="card border-0 img-hover-zoom">
                    <img
                        src="{{ asset('images/text-editor.svg') }}"
                        class="card-img-top" alt="{{ __('...') }}">
                    <div class="card-body">
                        <h5 class="card-title text-center">{{ __('Text Editor Online') }}</h5>
                    </div>
                </div>
            </div>
            <div class="col mb-4">
                <div class="card border-0 img-hover-zoom">
                    <img
                        src="{{ asset('images/free.svg') }}"
                        class="card-img-top" alt="{{ __('...') }}">
                    <div class="card-body">
                        <h5 class="card-title text-center">{{ __('Free Online Course') }}</h5>
                    </div>
                </div>
            </div>
            <div class="col mb-4">
                <div class="card border-0 img-hover-zoom">
                    <img
                        src="{{ asset('images/review-people.svg') }}"
                        class="card-img-top" alt="{{ __('...') }}">
                    <div class="card-body">
                        <h5 class="card-title text-center">{{ __('Review from an Expert') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
