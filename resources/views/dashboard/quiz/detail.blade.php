@extends('layouts.dash')

@push('css')
@endpush

@section('content')
    <div class="container mt-3">
        <div class="col">
            <div class="row align-items-center">
                <p class="h3 font-weight-bolder">Quiz</p>
                <div class="col">

                </div>
            </div>
            <hr class="mt-3 mb-3"/>
            <div class="row">
                <div class="card shadow border-0 w-100">
                    <div class="card-body">
                        <form action="{{ route('dashboard.quiz.store',$quiz->section_id) }}" method="post" >
                            @csrf
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="title">Title</label>
                                    <input type="text" name="title"
                                           class="form-control @error('title') is-invalid @enderror" id="title"
                                           placeholder="Insert your quiz title" required value="{{$quiz->title}}">
                                    @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col">
                                    <label for="description">Description</label>
                                    <textarea type="text" name="description"
                                           class="form-control @error('description') is-invalid @enderror"
                                           id="description" required>{{$quiz->description}}</textarea>
                                    @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col mt-3">
                                    <button type="submit" id="save-quiz" class="btn btn-primary pull-right shadow">Save and Next
                                    </button>
                                </div>
                            </div>
                        </form>
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

    </script>
@endpush
