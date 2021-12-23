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
                        <input type="email" class="form-control" id="email" placeholder="Your email">
                    </div>
                    <button type="submit" class="btn btn-blue mb-2 shadow text-white">Start Course</button>
                </form>
            @endguest
        </div>
    </div>
    <div class="container" style="margin-top: 5rem">
        <div class="row">
            <div class="col">
                <img style="width: 90%; height: 90%" src="{{ asset('images/home-people.svg') }}">
            </div>
            <div class="col">
                <p class="h3 font-weight-bold">Programming Language</p>
                @foreach($tags as $tag)
                    <div class="row w-100 d-block">
                        <a href="{{ route('courses', ['lang' => $tag->name]) }}" class="custom-card">
                            <div class="col mb-4">
                                <div class="card border-0 img-hover-zoom mt-2">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-2">
                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($tag->image) }}" height="60" width="60">
                                            </div>
                                            <div class="col">
                                                <span style="font-size: 18px" class="text-capitalize">{{ $tag->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

@endsection
