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
                <div class="h4 font-weight-bold">Detailed Information</div>
            </div>
            <hr class="mt-3 mb-5"/>
            <div class="row align-items-center">
                <span class="fa mr-3 h5"></span>
                <div class="h5 font-weight-bold">Course Objective(s)</div>
            </div>
            <hr class="mt-3 mb-3"/>
            <div class="row">
                <div class="card shadow border-0 w-100">
                    <div class="card-body">
                        <form action="{{ route('dashboard.course.objective.store', request()->route('id')) }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="objective">Objective</label>
                                    <input type="text" name="objective"
                                           class="form-control @error('objective') is-invalid @enderror" id="objective"
                                           placeholder="Insert your course objective" required>
                                    <button type="submit" class="btn btn-primary pull-right shadow mt-3">Add Objective
                                    </button>
                                    @error('objective')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </form>
                        <table class="table table-borderless table-striped">
                            <thead>
                            <tr>
                                <td style="width: 5%">No.</td>
                                <td>Objective</td>
                                <td style="width: 15%">Action</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($objectives as $objective)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $objective->title }}</td>
                                    <td>
                                        <a href="{{ route('dashboard.course.objective.destroy', [ 'id' => request()->route('id'), 'obj_id' => $objective->id]) }}"
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
                <div class="h5 font-weight-bold">Contributor(s)</div>
            </div>
            <hr class="mt-3 mb-3"/>
            <div class="row">
                <div class="card shadow border-0 w-100">
                    <div class="card-body">
                        <form action="{{ route('dashboard.course.contributor.store', request()->route('id')) }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="contributor">Contributor: </label>
                                    <select id="contributor" class="custom-select form-control @error('contributor') is-invalid @enderror" name="contributor"
                                            required>
                                        <option selected>Select Contributor Username</option>
                                        @foreach($users as $user)
                                            @if($user->account()->count() > 0)
                                                <option value="{{ $user->account->id }}"
                                                    {{ old('contributor') == $user->account->id ? 'selected' : ''}}>
                                                    {{ $user->account->fullname ? $user->account->fullname : $user->username }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('contributor')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col">
                                    <label for="as">As: </label>
                                    <select id="as" class="custom-select form-control @error('as') is-invalid @enderror" name="as" required>
                                        <option selected>Select Contributor Would Be</option>
                                        <option value="teacher">Teacher</option>
                                        <option value="reviewer">Reviewer</option>
                                    </select>
                                    @error('as')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <button type="submit" class="btn btn-primary pull-right shadow mt-3">Add
                                        Contributor
                                    </button>
                                </div>
                            </div>
                        </form>
                        <table class="table table-borderless table-striped">
                            <thead>
                            <tr>
                                <td style="width: 5%">No.</td>
                                <td>Contributor</td>
                                <td>As</td>
                                <td style="width: 15%">Action</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($contributors as $contributor)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $contributor->accounts->fullname ? $contributor->accounts->fullname : $contributor->accounts->user->username }}</td>
                                    <td class="text-capitalize">{{ $contributor->as }}</td>
                                    <td>
                                        <a href="{{ route('dashboard.course.contributor.destroy', [ 'id' => request()->route('id'), 'cont_id' => $contributor->id]) }}"
                                           role="button" class="btn btn-danger fa fa-trash"></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mt-3">
                    <a role="button" href="{{ route('dashboard.course.new.info', request()->route('id')) }}" class="btn btn-primary pull-right shadow">Save and Next</a>
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
