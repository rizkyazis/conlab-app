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

        .contributors-img {
            max-height: 4vw;
            max-width: 4vw;
            object-fit: cover;
        }

        .collapse-cursor {
            cursor: pointer;
        }

        td.no-underline,
        td.no-underline a:hover {
            text-decoration: none;
        }

        @media (min-width: 1405px) {
            .container-fluid {
                width: 1400px;
            }
        }

        .CodeMirror {
            border: 1px solid #eee;
            height: 500px;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid mt-3">
        <div class="row mt-5">
            <div class="col-8">
                <div class="row">
                    <div class="card w-100 shadow">
                        <div class="card-header">
                            <h5>{{$quiz->title}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row ">
                                <p>{{$quiz->description}}</p>
                            </div>
                            <div class="row">
                                @if(!empty($session))
                                    <p>Status : {{$session->status}}</p>
                                @else
                                    <p>Status : Not Taken</p>
                                @endif
                            </div>
                            <div class="row">
                                @if(!empty($session) && $session->status=='taken')
                                    <p>Score : {{$session->score}}</p>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{route('quiz.question',['id' => request()->route('id'), 'section_id' => request()->route('section_id'),$quiz->id])}}"
                                class="btn btn-primary">
                                Attempt
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="row row-cols-1">
                    <div class="col-12 mb-4">
                        <div class="card d-b border-0 shadow">
                            <h5 class="card-header card-header border-0">
                                Lesson & Quiz
                            </h5>
                            <div class="card-body">
                                @foreach($sections as $section)
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="font-weight-bold">{{ $section->title }}</h5>
                                            <table class="table table-striped table-borderless">
                                                @foreach($section->lessons()->get() as $lesson)
                                                    <tr>
                                                        <td class="no-underline">
                                                            <div class="row align-items-center">
                                                                <div class="col-2">
                                                                    <span class="fa fa-play-circle-o mr-3"></span>
                                                                </div>
                                                                <div class="col-10">
                                                                <span class="h6 font-weight-bold"><a
                                                                        href="{{ route('lesson', ['id' => request()->route('id'), 'section_id' => $section->id, 'lesson_id' => $lesson->id]) }}">{{ $lesson->title }}</a>
                                                                </span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="float-right">00:00</td>
                                                    </tr>
                                                @endforeach
                                                @foreach($section->quiz()->get() as $quizItem)
                                                    <tr>
                                                        <td class="no-underline">
                                                            <div class="row align-items-center">
                                                                <div class="col-2">
                                                                    <span class="fa fa-question-circle-o mr-3"></span>
                                                                </div>
                                                                <div class="col-10">
                                                                <span class="h6 font-weight-bold"><a
                                                                        href="{{route('quiz',[$section->course_id,$section->id,$quizItem->id])}}">{{$quizItem->title}}</a>
                                                                </span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="float-right">00:00</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush
