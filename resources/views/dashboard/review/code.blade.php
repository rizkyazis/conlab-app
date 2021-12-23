@extends('layouts.dash')

@push('css')
    <style>
        .CodeMirror {
            border: 1px solid #eee;
            height: 500px;
        }
    </style>
@endpush

@section('content')
    <div class="container mt-3">
        <div class="col">
            <div class="row row-cols-1">
                <div class="card shadow border-0">
                    <div class="card-header bg-white">
                        <h3>Review Code</h3>
                    </div>
                    <form
                        action="{{ route('review.update', ['id' => request()->route('id'), 'lesson_id' => request()->route('lesson_id'), 'account_id' => request()->route('account_id')]) }}"
                        method="post">
                        @csrf
                        <div class="card-body">
                            <div class="container">
                                <div class="row row-cols-1">
                                    <p>You're reviewing <span
                                            class="font-weight-bolder">{{ $account->fullname ? $account->fullname : $account->user->username }}</span>
                                        code</p>
                                </div>
                                <div class="row row-cols-2">
                                <textarea id="code"
                                          name="code">{{ old('code') ? old('code') : $code->raw_code }}</textarea>
                                    <iframe id=preview></iframe>
                                </div>
                                <div class="row row-cols-1 mt-3">
                                    <div class="form-group">
                                        <label for="feedback">Feedback (optional)</label>
                                        <textarea class="form-control" id="feedback" rows="3"
                                                  name="feedback">{{ old('feedback') ? old('feedback') : $code->feedback }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <label for="feedback">Score</label>
                                    <input class="form-control  @error('score') is-invalid @enderror" id="score" type="number" name="score"
                                           value="{{ old('score') ? old('score') : $code->score }}"/>
                                    @error('score')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary" id="submit-review" type="submit">Submit Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <button class="guide-float bg-info border-0 font-weight-bold text-white" data-toggle="modal" data-target="#Modal">
        Guide
    </button>
    @push('modal')
        <h1>Langkah Menambahkan Review dan Score</h1>
        <ol>
            <li>Masuk ke Menu Review</li>
            <li>Memilih course yang tersedia</li>
            <li>Memilih lesson yang tersedia</li>
            <li>Memilih siswa yang ingin di review dan diberi score</li>
            <li>Menambahkan review dan score untuk hasil pengerjaan siswa</li>
        </ol>
    @endpush
@endsection
@push('script')
    <script>
        @if($course->course_tags->tag->name === 'HTML')
        let mode = 'text/html';
        @elseif($course->course_tags->tag->name === 'Python')
        let mode = 'python';
        @endif
        $(document).ready(function () {
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

            runPython()
            @endif
        })
    </script>
@endpush
