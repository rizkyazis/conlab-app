@extends('layouts.dash')

@push('css')
@endpush

@section('content')
    <div class="container mt-3">
        <div class="col">
            <div class="row align-items-center">
                <p class="h3 font-weight-bolder">Update Course</p>
                <div class="col">

                </div>
            </div>
            <hr class="mt-3 mb-3"/>
            <div class="row align-items-center">
                <span class="fa fa-info mr-3 h5"></span>
                <div class="h4 font-weight-bold">General Information</div>
            </div>
            <hr class="mt-3 mb-3"/>
            <div class="row">
                <div class="card shadow border-0 w-100">
                    <div class="card-body">
                        <form action="{{ route('dashboard.course.update',$course->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="title">Title</label>
                                    <input type="text" name="title"
                                           class="form-control @error('title') is-invalid @enderror" id="title"
                                           placeholder="Insert your course title" required value="{{ $course->title  }}">
                                    @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col">
                                    <label for="description">Description</label>
                                    <input type="text" name="description"
                                           class="form-control @error('description') is-invalid @enderror"
                                           id="description" placeholder="Insert your course description" required
                                           value="{{ $course->description }}">
                                    @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="difficulty">Difficulty: </label>
                                    <select id="difficulty"
                                            class="custom-select form-control @error('difficulty') is-invalid @enderror"
                                            name="difficulty" required>
                                        <option {{ $course->difficulty ? '' : 'selected' }}>Select Difficulty</option>
                                        <option value="beginner" {{ $course->difficulty === 'beginner'? 'selected' : '' }}>Beginner</option>
                                        <option value="intermediate" {{ $course->difficulty === 'intermediate'? 'selected' : '' }}>Intermediate</option>
                                        <option value="advanced" {{ $course->difficulty === 'advanced'? 'selected' : '' }}>Advanced</option>
                                    </select>
                                    @error('difficulty')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col">
                                    <label for="cover">Cover Photo: </label>
                                    <div class="custom-file" id="cover">
                                        <input type="file" class="custom-file-input @error('img') is-invalid @enderror"
                                               id="img" name="img" >
                                        <label class="custom-file-label" for="img" id="img_label">Choose file</label>
                                        @error('img')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="tag">Category: </label>
                                    <select id="tag"
                                            class="custom-select form-control @error('category') is-invalid @enderror"
                                            name="category" required>
                                        <option {{ old('category') ? '' : 'selected' }}>Select Category</option>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" {{ $course->course_tags->tag_id == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <label for="editor">Course About</label>
                            <div id="editor"></div>
                            <div class="form-row">
                                <label for="about" hidden>Course About</label>
                                <textarea name="about" class="form-control" id="about" required hidden
                                          readonly>{{$course->about}};</textarea>
                            </div>
                            <div class="form-row">
                                <div class="col mt-3">
                                    <button type="submit" class="btn btn-primary pull-right shadow">Save and Next
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
            $('#img').on('change', function () {
                let fileName = event.target.files[0].name;
                $(this).next('#img_label').html(fileName);
                $(this).next('#img_label').css("overflow", "hidden");
                $(this).next('#img_label').css("white-space", "nowrap");
                $(this).next('#img_label').css("text-overflow", "ellipsis");
            });


            let editor = $('#editor').toastuiEditor({
                height: '500px',
                initialEditType: 'markdown',
                previewStyle: 'vertical',
                plugins: [codeSyntaxHighlight],
                initialValue : '{{$course->about}}',
                events: {
                    change: function () {
                        let about = $('#about').val(editor.toastuiEditor('getMarkdown'))
                        //console.log(about)
                    }
                }
            });
        })
    </script>
@endpush
