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
    <div class="container mt-5">
        <div class="row">
            <div class="col-7">
                <p class="h3 font-weight-bold">Programming Courses</p>
                <p class="small">Our goal is to be the #1 learning resource for Programming. We teach Programming
                    through live coding and videos.</p>
            </div>
        </div>
        <div class="row">
            <div class="col p-3 bg-white rounded m-3">
                <form class="" method="get" action="" id="form-skill">
                    <div class="row">
                        <div class="col-9">
                            <div class="form-group">
                                <input class="form-control" name="search" placeholder="Find your perfect course" value="{{ request()->search }}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-row">
                                <label for="skill" class="col col-form-label">Skill level: </label>
                                <div class="col-7">
                                    <select id="skill" name="skill" class="custom-select form-control">
                                        <option value="">All</option>
                                        <option value="beginner" {{ request()->skill === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                        <option value="intermediate" {{ request()->skill === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                        <option value="advanced" {{ request()->skill === 'advanced' ? 'selected' : '' }}>Advanced</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-3">
            @foreach($courses as $course)
                <div class="col mb-4">
                    <a href="{{ route('course', $course->id) }}" class="custom-card">
                        <div class="card border-0 img-hover-zoom">
                            <img
                                src="{{ Storage::url($course->img) }}"
                                class="card-img-top" alt="{{ __('...') }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $course->title }}</h5>
                                <p class="card-text">{{ $course->description }}</p>
                            </div>
                            <div class="card-footer border-0 bg-white">
                                {{ $course->tags->name??'' }}
                                <br>
                                {{ucfirst($course->difficulty)}}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(() => {
            $('#skill').on('change', () => {
                $('#form-skill').submit();
            })
        });
    </script>
@endpush
