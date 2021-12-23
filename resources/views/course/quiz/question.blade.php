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
                            @foreach($questions as $question)
                                @if($question->file_exist == true)
                                    @if($question->file->type === 'image')
                                        <img src="{{Storage::url($question->file->url)}}" alt="question_image">
                                    @elseif($question->file->type === 'video')
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe class="embed-responsive-item" src="{{Storage::url($question->file->url)}}" allowfullscreen></iframe>
                                        </div>
                                    @endif
                                @endif
                                <br>
                                <p>{{$question->question}}</p>
                                <input type="hidden" id="question_id" value="{{$question->id}}">
                                @foreach($question->answer as $answer)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answer"
                                               id="answer{{$loop->iteration}}" value="{{$answer->id}}"
                                               onclick="storeAnswer()"
                                               @foreach($session->answer as $sessionAnswer)
                                               @if($answer->id === $sessionAnswer->answer_id)
                                               checked
                                            @endif
                                            @endforeach
                                        >
                                        <label class="form-check-label" for="answer{{$loop->iteration}}">
                                            {{$answer->answer}}
                                        </label>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                        <div class="card-footer">
                            {{$questions->links()}}
                            <br>
                            <form action="{{route('quiz.submit',['id' => request()->route('id'), 'section_id' => request()->route('section_id'),'quiz_id'=>request()->route('quiz_id')])}}"
                            method="post">
                                @csrf
                                <button value="{{$session->id}}" name="session_id" class="btn btn-primary">Submit Answer</button>
                            </form>
                        </div>
                    </div>
                </div>
                <br>
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
    <script>
        function storeAnswer() {

            $.ajaxSetup({
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{route('quiz.question.answer',['id' => request()->route('id'), 'section_id' => request()->route('section_id'),'quiz_id'=>request()->route('quiz_id')])}}",
                method: "post",
                data: JSON.stringify({
                    session_id: "{{$session->id}}",
                    question_id: "{{$questions->first()->id}}",
                    answer_id: $('input[name="answer"]:checked').val()
                }),
                success: function (result) {
                    console.log(result);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert("Status: " + textStatus);
                    alert("Error: " + errorThrown);
                },
                dataType: "json",
                contentType: "application/json"
            });
        }
    </script>
@endpush
