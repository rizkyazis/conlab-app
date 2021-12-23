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
    </style>
@endpush
@section('content')
    <div class="jumbotron">
        <div class="container">
            <p class="h3 text-white font-weight-bold">Learn Programming now</p>
            <div class="my-1"></div>
            <p class="lead text-white">Join our courses to get started.</p>
        </div>
    </div>
    <div class="container">
        <b>Programming Language</b>
        <hr>
        @foreach($categories as $category)
            <div class="row">
                <a href="{{route('courses.category',$category->name)}}">
                    <img src="{{Storage::url($category->image)}}" alt="" class="w-25">
                    <p>{{$category->name}}</p>
                </a>
            </div>


        @endforeach
    </div>

@endsection
