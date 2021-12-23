@extends('layouts.app')

@push('css')
    <style>
        html {
            height: 100%;
        }

        .jumbotron {
            height: 100%;
            background: rgb(0, 123, 255);
            background: linear-gradient(180deg, rgba(0, 123, 255, 1) 57%, rgba(151, 202, 219, 1) 100%);
        }

        .card-img-top {
            width: 100%;
            height: 10vw;
            object-fit: cover;
        }

        .btn-rounded{
            border-radius: 40px;
        }

        .bg-gray{
            background: rgba(59, 56, 80, 0.1);
        }

        .card-body {
            width: 100%;
            height: 7vw;
        }

        .card {
            box-shadow: 0 0 0 grey;
            -webkit-transition: box-shadow .15s ease-out;
        }

        .card:hover {
            box-shadow: 1px 8px 20px grey;
            -webkit-transition: box-shadow .15s ease-in;
        }

        a.custom-card,
        a.custom-card:hover {
            color: inherit;
            text-decoration: none;
        }

        .custom-select {
            outline: 0;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid p-5">
        <div class="row">
            <div class="col-4">
                <p class="h2 font-weight-bold">Points</p>
                @foreach($rankings as $ranking)
                    @if(auth()->user()->account->id === $ranking['id'])
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-center">Your Point</h5>
                                <h1 class="text-center">{{$ranking['point']}}</h1>
                            </div>
                        </div>
                    @endif
                @endforeach

                <p class="h3 font-weight-bold mt-3">Rank Point</p>
                <table class="table table-striped table-borderless">
                    <thead class="border-bottom">
                    <tr>
                        <th>Ranking</th>
                        <th>Name</th>
                        <th>Point</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($rankings as $ranking)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$ranking['name']}}</td>
                                <td>{{$ranking['point']}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-8">
                <p class="h2 font-weight-bold">Score and Review</p>
                <div class="container-fluid border h-100 p-4">
                    <div class="row">
                        <div class="col-4">
                            <form>
                                <div class="form-group">
                                    <label for="programming_language">Programming Language</label>
                                    <select class="form-control border border-primary" id="programming_language" name="programming_language">
                                        <option value="">Select programming language</option>
                                    </select>
                                </div>
                                <div class="form-group" id="course_component">
                                    <label for="course">Course</label>
                                    <select class="form-control border border-primary" id="course" name="course">
                                        <option value="">Select course</option>
                                    </select>
                                </div>
                                <div class="form-group" id="cert_component">
                                    <button class="btn btn-primary btn-block btn-rounded font-weight-bolder shadow btn-cert">Certification</button>
                                </div>
                                <div class="form-group" id="section_component">
                                    <label for="section">Section</label>
                                    <select class="form-control border border-primary" id="section" name="section">
                                        <option value="">Select section</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col" id="lesson_component">
                            <p class="font-weight-bold">Lesson</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>

        $(document).ready(() => {
            let pl = $('#programming_language');
            let course = $('#course');
            let section = $('#section');

            // comp
            let course_comp = $('#course_component');
            let cert_comp = $('#cert_component');
            let section_comp = $('#section_component');
            let lesson_comp = $('#lesson_component');

            course_comp.hide();
            section_comp.hide();
            cert_comp.hide();

            axios({
                method: 'GET',
                url: '{{ route('score.languages') }}'
            }).then(response => {
                for(let i = 0; i < response.data.tags.length; i++){
                    pl.append($('<option></option>').text(response.data.tags[i].name).attr('value', response.data.tags[i].id));
                }
            });

            pl.on('change', () => {
                if(pl.val() === ''){
                    return;
                }

                course_comp.show();

                axios({
                    method: 'GET',
                    url: '{{ route('score.courses') }}?tag_id=' + pl.val()
                }).then(response => {
                    if(!$('.lesson_section').empty()){
                        $('.lesson_section').remove();
                    }

                    if(!$('.btn_section').empty()){
                        $('.btn_section').remove();
                    }
                    section.empty();
                    section.append('<option value="">Select section</option>');
                    course.empty();
                    course.append('<option value="">Select course</option>')
                    for (let i = 0; i < response.data.courses.length; i++){
                        course.append($('<option></option>').text(response.data.courses[i].title).attr('value', response.data.courses[i].id));
                    }
                });
            })

            course.on('change', () => {
                if(course.val() === ''){
                    return;
                }

                section_comp.show();
                cert_comp.show();

                axios({
                    method: 'GET',
                    url: '{{ route('score.sections') }}?course_id=' + course.val()
                }).then(response => {
                    if(!$('.lesson_section').empty()){
                        $('.lesson_section').remove();
                    }

                    if(!$('.btn_section').empty()){
                        $('.btn_section').remove();
                    }
                    section.empty();
                    section.append('<option value="">Select section</option>');
                    for(let i = 0; i < response.data.sections.length; i++){
                        section.append($('<option></option>').text(response.data.sections[i].title).attr('value', response.data.sections[i].id))
                    }

                    $('.btn-cert').on('click', () => {
                        window.open(`/course/${course.val()}/certificate`);
                        swal('Certificate printed!');
                    })
                });
            })

            section.on('change', function(){
                axios({
                    method: 'GET',
                    url: '{{ route('score.lessons') }}?section_id=' + section.val()
                }).then(response => {
                    if(!$('.lesson_section').empty()){
                        $('.lesson_section').remove();
                    }

                    if(!$('.btn_section').empty()){
                        $('.btn_section').remove();
                    }

                    let lesson_data = response.data.lessons;
                    for(let y = 0; y < lesson_data.length; y++){
                        let lesson = lesson_data[y];

                        if(lesson.codes.length === 0){
                            lesson_comp.append(`
                                    <div class="row-cols-1 mt-3 lesson_section">
                                        <div class="bg-gray p-4">
                                            <p class="h5 text-primary font-weight-bolder">${lesson.title}</p>
                                            <p>No score and review needed.</p>
                                        </div>
                                    </div>`
                            );
                        }else{
                            if(lesson.codes[0].is_reviewed === 1){
                                lesson_comp.append(`
                                    <div class="row-cols-1 mt-3 lesson_section">
                                        <div class="bg-gray p-4">
                                            <p class="h5 text-primary font-weight-bolder">${lesson.title}</p>
                                            <p class="h1 font-weight-bolder">${lesson.codes[0].score}</p>
                                            <p>${lesson.codes[0].feedback}</p>
                                        </div>
                                    </div>
                                    <div class="row-cols-1 text-right btn_section">
                                        <a href="/course/${course.val()}/section/${section.val()}/lesson/${lesson.id}" class="btn btn-primary mt-3">See your code</a>
                                    </div>
                                `);
                            }else {
                                lesson_comp.append(`
                                    <div class="row-cols-1 mt-3 lesson_section">
                                        <div class="bg-gray p-4">
                                            <p class="h5 text-primary font-weight-bolder">${lesson.title}</p>
                                            <p>Your code is not reviewed yet</p>
                                        </div>
                                    </div>
                                    <div class="row-cols-1 text-right btn_section">
                                        <a href="/course/${course.val()}/section/${section.val()}/lesson/${lesson.id}" class="btn btn-primary mt-3">See your code</a>
                                    </div>
                                `);
                            }
                        }
                    }
                });
            })
        });
    </script>
@endpush
