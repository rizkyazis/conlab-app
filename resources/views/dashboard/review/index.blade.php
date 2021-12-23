@extends('layouts.dash')

@push('css')
@endpush

@section('content')
    <div class="container mt-3">
        <div class="col">
            <div class="row row-cols-1">
                <div class="card shadow border-0">
                    <div class="card-header bg-white">
                        <div class="container">
                            <div class="row row-cols-2">
                                <h3>Select Course to Review</h3>
                                <form class="float-right"
                                      action="{{route('course.review.search')}}"
                                      method="get">
                                    <input type="text" class="form-control" name="name" id="name"
                                           placeholder="Search Course Title" data-placement="left">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-borderless">
                            <thead class="border-bottom">
                            <tr>
                                <td width="10%">No.</td>
                                <td>Course Title</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($courses as $course)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a id="course-{{ $loop->iteration }}" href="{{ route('review.lessons', $course->id) }}">{{ $course->title }}</a></td>
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
