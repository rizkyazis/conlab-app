@extends('layouts.dash')

@push('css')
@endpush

@section('content')
    <div class="container mt-3">
        <div class="col">
            <div class="row align-items-center">
                <p class="h3 font-weight-bolder">Create New Course</p>
            </div>
            <hr class="mt-3 mb-3"/>
            <div class="row align-items-center">
                <span class="fa fa-info mr-3 h5"></span>
                <div class="h4 font-weight-bold">Course Information</div>
            </div>
            <hr class="mt-3 mb-5"/>
            <div class="row align-items-center">
                <span class="fa mr-3 h5"></span>
                <div class="h5 font-weight-bold">Course Section(s)</div>
            </div>
            <hr class="mt-3 mb-3"/>
            <div class="row">
                <div class="card shadow border-0 w-100">
                    <div class="card-body">
                        <form action="{{ route('dashboard.course.section.store', request()->route('id')) }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="section">Section</label>
                                    <input type="text" name="section"
                                           class="form-control @error('section') is-invalid @enderror" id="section"
                                           placeholder="Insert your course section" required>
                                    @error('section')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <button type="submit" class="btn btn-primary pull-right shadow mt-3">Add Section
                                    </button>
                                </div>
                            </div>
                        </form>
                        <table class="table table-borderless table-striped">
                            <thead>
                            <tr>
                                <td style="width: 5%">No.</td>
                                <td>Section</td>
                                <td style="width: 15%">Action</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sections as $section)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $section->title }}</td>
                                    <td>
                                        <a href="{{ route('dashboard.course.section.destroy', [ 'id' => request()->route('id'), 'section_id' => $section->id]) }}"
                                           role="button" class="btn btn-danger fa fa-trash"></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <hr class="mt-3 mb-5"/>
            <div class="row align-items-center">
                <span class="fa mr-3 h5"></span>
                <div class="h5 font-weight-bold">Course Lesson(s)</div>
            </div>
            <hr class="mt-3 mb-3"/>
            <div class="row">
                <div class="card shadow border-0 w-100">
                    <div class="card-body">
                        <form action="{{ route('dashboard.course.lesson.store', request()->route('id')) }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="title">Title</label>
                                    <input name="title" id="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           placeholder="What is your Lesson title?" required value="{{ old('title') }}">
                                    @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="section_id">Section:</label>
                                    <select id="section_id"
                                            class="custom-select form-control @error('section_id') is-invalid @enderror"
                                            name="section_id" required>
                                        <option selected>Select Section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ old('section_id') === $section->id ? 'selected' : '' }}>{{ $section->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col">
                                    <label for="is_coding">Is student must Code?</label>
                                    <select id="is_coding"
                                            class="custom-select form-control @error('is_coding') is-invalid @enderror"
                                            name="is_coding" required>
                                        <option value="yes" {{ old('is_coding') === 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ old('is_coding') === 'no' ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('is_coding')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="is_video">Is Video Available?</label>
                                    <select id="is_video"
                                            class="custom-select form-control @error('is_video') is-invalid @enderror"
                                            name="is_video" required>
                                        <option value="yes" {{ old('is_video') === 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ old('is_video') === 'no' ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('is_video')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row" id="video-link">
                                <div class="form-group col">
                                    <label for="video-int">Upload Video: </label>
                                    <div class="custom-file" id="cover">
                                        <input type="file"
                                               class="custom-file-input @error('video-int') is-invalid @enderror"
                                               id="video-int" name="video-int">
                                        <label class="custom-file-label" for="video-int" id="video-int-label">Choose
                                            file</label>
                                        @error('video-int')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <label for="editor">Lesson Description</label>
                            <div id="editor"></div>
                            <div class="form-row">
                                <label for="description" hidden>Course About</label>
                                <textarea name="description" class="form-control" id="description" required hidden
                                          readonly></textarea>
                            </div>
                            <div class="form-row">
                                <div class="col mt-3">
                                    <button type="submit" class="btn btn-primary pull-right shadow">Save Course Lesson
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <hr class="mt-3 mb-5"/>
            <div class="row align-items-center">
                <span class="fa mr-3 h5"></span>
                <div class="h5 font-weight-bold">Course Lesson List(s)</div>
            </div>
            <div class="row">
                <div class="card shadow border-0 w-100">
                    <div class="card-body">

                        <table class="table table-borderless table-striped">
                            <thead>
                            <tr>
                                <td style="width: 5%">No.</td>
                                <td style="width: 20%">Section</td>
                                <td>Lesson Title</td>
                                <td style="width: 15%">Action</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lessons as $lesson)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $lesson->section->title }}</td>
                                    <td>{{ $lesson->title }}</td>
                                    <td><a href="{{ route('dashboard.course.lesson.destroy', [ 'id' => request()->route('id'), 'lesson_id' => $lesson->id]) }}"
                                           role="button" class="btn btn-danger fa fa-trash"></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mt-3">
                    <a role="button" href="{{route('dashboard.course.index')}}" class="btn btn-primary pull-right shadow">Finish</a>
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
            let is_video = $('#is_video')

            // Hide or show video section onchange
            is_video.on('change', function () {
                if (is_video.val() === 'yes') {
                    $('#video-alert').attr('hidden', false)
                    $('#video-link').attr('hidden', false)
                } else {
                    $('#video-alert').attr('hidden', true)
                    $('#video-link').attr('hidden', true)
                }
            })

            // Hide or show video section onload
            if (is_video.val() === 'yes') {
                $('#video-alert').attr('hidden', false)
                $('#video-link').attr('hidden', false)
            } else {
                $('#video-alert').attr('hidden', true)
                $('#video-link').attr('hidden', true)
            }


            $('#video-int').on('change', function () {
                let fileName = event.target.files[0].name;
                $(this).next('#video-int-label').html(fileName);
                $(this).next('#video-int-label').css("overflow", "hidden");
                $(this).next('#video-int-label').css("white-space", "nowrap");
                $(this).next('#video-int-label').css("text-overflow", "ellipsis");
            });

            let editor = $('#editor').toastuiEditor({
                height: '500px',
                initialEditType: 'markdown',
                previewStyle: 'vertical',
                plugins: [codeSyntaxHighlight],
                events: {
                    change: function () {
                        let description = $('#description').val(editor.toastuiEditor('getMarkdown'))
                        //console.log(description)
                    }
                }
            });
        })
    </script>
@endpush
