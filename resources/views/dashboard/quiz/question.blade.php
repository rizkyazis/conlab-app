@extends('layouts.dash')

@push('css')
@endpush

@section('content')
    <div class="container mt-3">
        <div class="col">
            <div class="row align-items-center">
                <p class="h3 font-weight-bolder">{{$quiz->title}}</p>
                <div class="col">

                </div>
            </div>
            <hr class="mt-3 mb-3"/>
            <div class="row">
                <div class="card shadow border-0 w-100">
                    <div class="card-body">
                        <form action="{{ route('dashboard.quiz.question.store',$quiz->section_id) }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-9">
                                    <label for="question">Question</label>
                                    <input type="text" name="question"
                                           class="form-control @error('question') is-invalid @enderror" id="question"
                                           placeholder="Insert your quiz question" required value="{{old('question')}}">
                                    @error('question')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-3">
                                    <label for="file">File</label>
                                    <input type="file" name="file"
                                           class="form-control @error('file') is-invalid @enderror">
                                    @error('file')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div id="dynamic-option">
                                <div class="form-row" id="option[0]">
                                    <div class="form-group col-9">
                                        <label for="answer">Option</label>
                                        <input type="text" name="answer[0]"
                                               class="form-control"
                                               placeholder="Insert your quiz answer" required>
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="answer">Point</label>
                                        <input type="number" name="point[0]"
                                               class="form-control"
                                               value="0"
                                               placeholder="Insert your answer point">
                                    </div>
                                </div>
                            </div>
                            <button onclick="addOption()"
                                    id="add-option"
                                    class="btn btn-primary ">
                                Add Option
                            </button>
                            <div class="form-row">
                                <div class="col mt-3">
                                    <button type="submit" id="sumbit-question" class="btn btn-primary pull-right shadow">Save Question
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <br class="mt-3 mb-3"/>
            <div class="row">
                <div class="card shadow border-0 w-100">
                    <div class="card-body">
                        <table class="table table-striped table-borderless">
                            <thead class="border-bottom">
                            <tr>
                                <th width="10%">No.</th>
                                <th>Question</th>
                                <th>Point</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($questions as $question)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="w-50">{{ $question->question}}</td>
                                    <td>
                                        @foreach($question->answer as $answer)
                                            @if($answer->point>0)
                                                [{{$answer->point}}]
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="row">
                                            <button class="btn btn-sm btn-primary col" value="{{$question->id}}"
                                                    onclick="previewQuestion(this)">Preview
                                            </button>
                                            <form class="col"
                                                  action="{{route('dashboard.quiz.question.delete',$question->id) }}"
                                                  method="post">
                                                @csrf
                                                <button class="btn btn-sm btn-danger w-100" type="submit">Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <button type="button" id="buttonModal" class="btn btn-primary d-none" data-toggle="modal"
                                data-target=".bd-example-modal-lg">Large modal
                        </button>
                        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                             aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Question Preview</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-md">
                                            <img id="imageQuestion"
                                                 src="https://www.cnet.com/a/img/-qQkzFVyOPEoBRS7K5kKS0GFDvk=/940x0/2020/04/16/7d6d8ed2-e10c-4f91-b2dd-74fae951c6d8/bazaart-edit-app.jpg"
                                                 alt="" class="d-none">
                                            <div id="videoQuestion" class="d-none">
                                                <iframe id="videoQuestionFile" class="embed-responsive-item"
                                                        src="https://youtu.be/K09_5IsgGe8"
                                                        allowfullscreen></iframe>
                                            </div>
                                            <hr id="hr" class="d-none">
                                            <p id="previewQuestion">Question</p>
                                            <div id="optionQuestion">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="guide-float bg-info border-0 font-weight-bold text-white" data-toggle="modal" data-target="#Modal">
        Guide
    </button>
    @push('modal')
        <h1>Langkah Membuat Kuis atau Mengubah Kuis</h1>
        <ol>
            <li>Masuk ke Menu Quiz</li>
            <li>Memilih course yang tersedia untuk Quiz <br>
                *Apabila course belum tersedia, silahkan untuk membuat course baru pada menu Course</li>
            <li>Memilih section yang tersedia untuk Quiz <br> *Apabila section belum tersedia, silahkan untuk membuat atau mengubah section pada menu Course</li>
            <li>Mengisi judul quiz</li>
            <li>Mengisi deskripsi quiz</li>
            <li>Menambahkan pertanyaan dan jawaban serta nilai dan poin untuk quiz</li>
        </ol>
    @endpush
@endsection
@push('script')
    <script>
        var i = 0;

        function addOption() {
            i++
            $('#dynamic-option').append(
                '<div class="form-row" id="option[' + i + ']">'
                + '<div class="form-group col-9">'
                + '<input type="text" name="answer[' + i + ']"'
                + 'class="form-control"'
                + 'placeholder="Insert your quiz answer" required >'
                + '</div>'
                + '<div class="form-group col-3" >'
                + '<input type="number" name="point[' + i + ']"'
                + 'class="form-control"'
                + 'value="0"'
                + 'placeholder="Insert your answer point">'
                + '</div>'
                + '</div>')
        }

        function showOption(answer,point){
            $('#optionQuestion').append(
                '<div class="form-check">'
                +'<input class="form-check-input" type="radio" name="answer">'
                +'<label class="form-check-label">'+answer+' ['+point+']</label>'
                +'</div>'
            )
        }

        function previewQuestion(element) {
            $.ajaxSetup({
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{route('preview.question')}}",
                method: "post",
                data: JSON.stringify({
                    id: element.value,
                }),
                success: function (result) {
                    document.getElementById('previewQuestion').innerHTML = result.question;
                    let image = document.getElementById('imageQuestion');
                    let video = document.getElementById('videoQuestion');
                    let hr = document.getElementById('hr');
                    let option = document.getElementById('optionQuestion');
                    if (result.file_exist == 1) {
                        if (result.file.type == 'image') {
                            hr.className = '';
                            image.className = 'w-50';
                            video.className = 'd-none';
                            let imageSource = (result.file.url).replace('public', '');
                            image.src = '/storage' + imageSource;
                            document.getElementById('videoQuestionFile').src = '';
                        } else {
                            hr.className = '';
                            image.className = 'd-none';
                            video.className = 'embed-responsive embed-responsive-16by9';
                            let videoSource = (result.file.url).replace('public', '');
                            document.getElementById('videoQuestionFile').src = '/storage' + videoSource;
                        }
                    } else {
                        hr.className = 'd-none';
                        image.className = 'd-none';
                        video.className = 'd-none';
                        document.getElementById('videoQuestionFile').src = '';
                    }
                    option.innerHTML = '';
                    (result.answer).forEach(answer => showOption(answer.answer,answer.point));
                    document.getElementById('buttonModal').click();
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
