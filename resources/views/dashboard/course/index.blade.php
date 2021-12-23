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
                                <h3>List Course</h3>
                                <form class="float-right"
                                      action="{{route('dashboard.course.search')}}"
                                      method="get">
                                    <input type="text" class="form-control" name="name" id="name"
                                           placeholder="Search Course Title" data-placement="left">
                                </form>
                            </div>
                        </div>
                        <a class="btn btn-primary" id="add-course" href="{{route('dashboard.course.new')}}">Add New Course</a>
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
                                    <td>
                                        <a href="{{ route('dashboard.course.detailed', $course->id) }}">{{ $course->title }}</a>
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
        <h1>Langkah Membuat Kursus atau Mengubah Kursus</h1>
        <ol>
            <li>Masuk ke Menu Course</li>
            <li>Memilih untuk menambahkan kursus baru dengan menekan button ‘Add New Course’ atau
                mengubah course yang ada dengan memilih course yang ingin diubah</li>
            <li>Masuk pada sesi  General Information tentang course</li>
            <ul>
                <li>Mengisi Judul Materi</li>
                <li>Mengisi Deskripsi Materi</li>
                <li>Mengisi Tingkat Kesulitan Materi pada field ‘Difficulty'</li>
                <li>Menambahkan Cover Photo</li>
                <li>Memilih kategori bahasa pemrograman</li>
            </ul>
            <li>Masuk pada sesi Detailed Information</li>
            <ul>
                <li>Mengisi Objective Course</li>
                <li>Menambahkan Kontributor Course</li>
            </ul>
            <li>Masuk pada sesi Course Information</li>
            <ul>
                <li>Mengisi Section Course</li>
                <li>Mengisi Lesson Course</li>
            </ul>
        </ol>
    @endpush
@endsection
@push('script')
    <script>
        $(document).ready(function () {

        })
    </script>
@endpush
