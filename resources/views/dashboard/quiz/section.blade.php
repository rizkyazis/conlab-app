
@extends('layouts.dash')

@push('css')
@endpush

@section('content')
    <div class="container mt-3">
        <div class="col">
            <div class="row row-cols-1">
                <div class="card shadow border-0">
                    <div class="card-header bg-white">
                        <h3>Select Section</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-borderless">
                            <thead class="border-bottom">
                            <tr>
                                <th width="10%">No.</th>
                                <th>Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sections as $section)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a id="section-{{ $loop->iteration }}" href="{{route('dashboard.quiz.detail',$section->id)}}">{{$section->title}}</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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
