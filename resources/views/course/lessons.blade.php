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
        @if($lesson->is_video === 1)
            <div class="row">
                <div class="col">
                    <video id="player" playsinline controls class="w-100 h-100">
                        <source src="{{ Storage::url($lesson->video->url) }}" type="video/mp4"/>
                    </video>
                </div>
            </div>
        @endif
        <div class="row mt-5">
            <div class="col-8">
                <div class="row">
                    <div class="col">
                        @markdown($lesson->description)
                    </div>
                </div>
                @if($lesson->is_coding === 1)
                    <div class="row mt-5">
                        <div class="card w-100 shadow">
                            <form
                                action="{{ route('lesson.code.store', ['id' => request()->route('id'), 'section_id' => request()->route('section_id'), 'lesson_id' => $lesson->id]) }}"
                                method="post">
                                @csrf
                                <div class="card-header">
                                    <h5>Write your code here</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row row-cols-2">
                                        <textarea id="code"
                                                  name="code">{{ !empty($code) ? $code->raw_code : ''}}</textarea>
                                        <iframe id=preview></iframe>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button name="sumbit" class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </form>
                            @if($course->course_tags->tag->name === 'Python')
                                <button class="btn btn-primary" onclick="runPython()">run</button>
                            @endif
                        </div>
                    </div>
                @endif
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
                                                                <span class="h6 @if($points->contains('lesson_id',$lesson->id)) font-weight-bold @endif"><a
                                                                        href="{{ route('lesson', ['id' => request()->route('id'), 'section_id' => $section->id, 'lesson_id' => $lesson->id]) }}">{{ $lesson->title }}</a>
                                                                </span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="float-right">
                                                            @if($lesson->is_coding == 1)
                                                                @if($points->contains('lesson_id',$lesson->id))
                                                                    @if($points != [])
                                                                        @foreach($points as $point)
                                                                            @if($point->lesson_id === $lesson->id)
                                                                                @if($point->point=='0')
                                                                                    0/15 Points
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
                                                                                    {{$point->point}}/5 Points
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
                                                                <span class="h6 @if($points->contains('quiz_id',$quizItem->id)) font-weight-bold @endif"><a
                                                                        href="{{route('quiz',[$section->course_id,$section->id,$quizItem->id])}}"><b></b>{{$quizItem->title}}</a>
                                                                </span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="float-right">
                                                            @if($points->contains('quiz_id',$quizItem->id))
                                                                @foreach($points as $point)
                                                                    @if($point->quiz_id === $quizItem->id)
                                                                        {{$point->point}}/15 Points
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                0/15 Points
                                                            @endif</td>
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
    <script src="https://cdn.plyr.io/3.6.2/plyr.js"></script>
    <script>
        @if($course->course_tags->tag->name === 'HTML')
        let mode = 'text/html';
        @elseif($course->course_tags->tag->name === 'Python')
        let mode = 'python';
        @endif
        $(document).ready(function () {
            const controls = ['play', 'progress', 'current-time', 'duration', 'mute', 'volume', 'settings', 'pip'];
            const settings = ['quality', 'speed', 'loop'];
            const player = new Plyr('#player', {
                title: 'Example Title',
                disableContextMenu: true,
                controls,
                settings,
                quality: {
                    options: [1080, 720, 480, 360, 240]
                }
            });
        });

        let delay;
        // Initialize CodeMirror editor with a nice html5 canvas demo.
        let editor = CodeMirror.fromTextArea(document.getElementById('code'), {
            mode: mode,
            lineNumbers: true,
            matchBrackets: true,
            theme: 'dracula',
        });

        @if($course->course_tags->tag->name === 'HTML')
        editor.on("change", function () {
            clearTimeout(delay);
            delay = setTimeout(updatePreview, 300);
        });

        function updatePreview() {
            let previewFrame = document.getElementById('preview');
            let preview = previewFrame.contentDocument || previewFrame.contentWindow.document;
            preview.open();
            preview.write(editor.getValue());
            preview.close();
        }

        setTimeout(updatePreview, 300);
        @endif
        @if($course->course_tags->tag->name === 'Python')
        function runPython() {

            $.ajaxSetup({
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{route('run.python')}}",
                method: "post",
                data: JSON.stringify({
                    code: editor.getValue(),
                }),
                success: function (result) {
                    let previewFrame = document.getElementById('preview');
                    let preview = previewFrame.contentDocument || previewFrame.contentWindow.document;
                    preview.open();
                    preview.write(result);
                    preview.close();
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert("Status: " + textStatus);
                    alert("Error: " + errorThrown);
                },
                dataType: "json",
                contentType: "application/json"
            });
        }

        @if(!empty($code))
        runPython()
        @endif
        @endif

    </script>
@endpush
