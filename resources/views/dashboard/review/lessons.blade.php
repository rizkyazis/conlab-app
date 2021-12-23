@extends('layouts.dash')

@push('css')
@endpush

@section('content')
    <div class="container mt-3">
        <div class="col">
            <div class="row row-cols-1">
                <div class="card shadow border-0">
                    <div class="card-header bg-white">
                        <h3>Select Lesson to review</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-borderless">
                            <thead class="border-bottom">
                            <tr>
                                <td width="10%">No.</td>
                                <td>Lesson Title</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lessons as $lesson)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a id="lesson-{{ $loop->iteration }}" href="{{ route('review.participants', ['id' => request()->route('id'), 'lesson_id' => $lesson->id]) }}">{{ $lesson->title }}</a>
                                    </td>
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
        $(document).ready(function () {

        })
    </script>
@endpush
