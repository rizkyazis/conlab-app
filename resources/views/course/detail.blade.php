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

        .contributors-img {
            height: 4vw;
            width: 4vw;
            object-fit: cover;
        }

        .collapse-cursor {
            cursor: pointer;
        }

        td.no-underline,
        td.no-underline a:hover {
            text-decoration: none;
        }
    </style>
@endpush
@section('content')
    <div class="jumbotron">
        <div class="container">
            <div class="row">
                <div class="col">
                    <p class="h3 text-white font-weight-bold">{{ $course->title }}</p>
                    <div class="my-1"></div>
                    <p class="lead text-white">{{ $course->description }}</p>
                    <a id="enroll-course" href="{{route('enroll.course',$course->id)}}"
                       class="btn btn-blue mb-2 text-white"><span class="fa fa-graduation-cap"></span> Start
                        Course
                    </a>
                </div>
                <div class="col">

                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-8">
                <div class="row container">
                    <div class="col">
                        @markdown($course->about)
                    </div>
                </div>
                <div class="row container">
                    <div class="col">
                        @foreach($sections as $section)
                            <div class="row row-cols-1">
                                <div class="col-12 mb-4">
                                    <div class="card d-b border-0 shadow">
                                        <h5 class="card-header card-header border-0 bg-white">
                                        <span data-toggle="collapse" href="#section-collapse-{{ $loop->iteration }}"
                                              aria-expanded="true"
                                              aria-controls="section-collapse-{{ $loop->iteration }}"
                                              id="heading-{{ $loop->iteration }}"
                                              class="d-block collapse-cursor">

                                            <i class="fa fa-chevron-down pull-right"></i><span class="small pull-right">@if($enroll == 'yes')
                                                    Point Received @endif</span>

                                                #{{ $loop->iteration }} {{ $section->title }}

                                        </span>
                                        </h5>
                                        <div id="section-collapse-{{ $loop->iteration }}" class="collapse show"
                                             aria-labelledby="heading-{{ $loop->iteration }}">
                                            <div class="card-body">
                                                <table class="table table-striped table-borderless">
                                                    @foreach($section->lessons()->get() as $lesson)
                                                        <tr>
                                                            <td class="no-underline">
                                                                <span class="fa fa-play-circle-o mr-3"></span>
                                                                <span class="h5 font-weight-bold">
                                                                <a href="{{ route('lesson', ['id' => request()->route('id'), 'section_id' => $section->id, 'lesson_id' => $lesson->id]) }}">{{ $lesson->title }}</a>
                                                            </span>
                                                            </td>
                                                            @auth
                                                            <td class="float-right" name="detail-point">
                                                                @if($lesson->is_coding == 1)
                                                                    @if($points->contains('lesson_id',$lesson->id))
                                                                        @if($points != [])
                                                                            @foreach($points as $point)
                                                                                @if($point->lesson_id === $lesson->id)
                                                                                    @if($point->point=='0')
                                                                                        Your code is not reviewed yet
                                                                                    @else
                                                                                        {{$point->point}}/15 Points
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        @else
                                                                            0/15 Points
                                                                        @endif
                                                                    @else
                                                                        0/15 Points
                                                                    @endif
                                                                    @else
                                                                    @if($points->contains('lesson_id',$lesson->id))
                                                                        @if($points != [])
                                                                            @foreach($points as $point)
                                                                                @if($point->lesson_id === $lesson->id)
                                                                                    @if($point->point=='0')
                                                                                        Your code is not reviewed yet
                                                                                    @else
                                                                                        {{$point->point}}/5 Points
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        @else
                                                                            0/5 Points
                                                                        @endif
                                                                    @else
                                                                        0/5 Points
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            @endauth
                                                        </tr>
                                                    @endforeach
                                                    @foreach($section->quiz()->get() as $quiz)
                                                        <tr>
                                                            <td class="no-underline">
                                                                <span class="fa fa-question-circle-o mr-3"></span>
                                                                <span class="h5 font-weight-bold">
                                                                <a href="{{ route('quiz', ['id' => request()->route('id'), 'section_id' => $section->id, 'quiz_id' => $quiz->id]) }}">{{ $quiz->title }}</a>
                                                            </span>
                                                            </td>
                                                            @auth
                                                                <td class="float-right">
                                                                    @if($points->contains('quiz_id',$quiz->id))
                                                                    @foreach($points as $point)
                                                                        @if($point->quiz_id === $quiz->id)
                                                                            {{$point->point}}/15 Points
                                                                        @endif
                                                                    @endforeach
                                                                    @else
                                                                    0/15 Points
                                                                    @endif
                                                                </td>
                                                            @endauth
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="row"><p class="h3">What will you learn</p></div>
                @foreach($objectives as $objective)
                    <div class="row align-content-center align-items-center">
                        <div class="col-1">
                            <span class="fa fa-check mr-3"></span>
                        </div>
                        <div class="col-11">
                            {{ $objective->title }}
                        </div>
                    </div>
                @endforeach

                <div class="row mt-3"><p class="h3">Who's behind the course</p></div>
                @foreach($contributors as $contributor)
                    <div class="row align-content-center align-items-center mt-2">
                        <img
                            src="{{$contributor->accounts->photo ? Storage::url($contributor->accounts->photo) : asset('images/profile-pict.png')}}"
                            class="contributors-img rounded-circle" alt="...">
                        <div class="col ml-3">
                            <div
                                class="row font-weight-bold text-capitalize">
                                {{ $contributor->accounts->fullname ? $contributor->accounts->fullname : $contributor->accounts->user->username }}
                                -
                                {{ $contributor->as }}</div>
                            {{--<div class="row">
                                Description Description Description Description Description Description
                            </div>--}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        point = document.getElementsByName('detail-point');
        for (let i = 0; i < point.length; i++) {
            if (point[i].innerHTML == '') {
                point[i].innerHTML = '0 Points'
            }
        }
    </script>
@endpush
