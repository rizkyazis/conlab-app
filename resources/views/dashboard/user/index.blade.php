@extends('layouts.dash')

@push('css')
    <style>
        .photo-user {
            height: 3vw;
            width: 3vw;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="container mt-3">
        <div class="col">
            <div class="row row-cols-1">
                <div class="card shadow border-0">
                    <div class="card-header bg-white">
                        <div class="container">
                            <div class="row row-cols-2">
                                <h3>List User</h3>
                                <form class="float-right"
                                      action="{{route('user.search')}}"
                                      method="get">
                                    <input type="text" class="form-control" name="name" id="name"
                                           placeholder="Search User Name" data-placement="left">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-borderless">
                            <thead class="border-bottom">
                            <tr>
                                <th width="10%">No.</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Role</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><img onclick="fullImage(this)"
                                             src="{{$user->account->photo ? Storage::url($user->account->photo): asset('images/profile-pict.png')}}"
                                             alt="" class="photo-user"></td>
                                    <td>{{$user->account->fullname ? $user->account->fullname : $user->username}}</td>
                                    <td>
                                        <form id="roleChange{{$user->id}}" action="{{route('user.role',$user->id)}}"
                                              method="post">
                                            @csrf
                                            @method('PUT')
                                            <select name="role" class="form-control form-control-sm w-50"
                                                    onchange="roleChange(this)" id="user-{{$user->id}}">
                                                <option value="admin" @if('admin'===$user->role)selected @endif>Admin
                                                </option>
                                                <option value="teacher" @if('teacher'===$user->role)selected @endif>
                                                    Teacher
                                                </option>
                                                <option value="reviewer" @if('reviewer'===$user->role)selected @endif>
                                                    Reviewer
                                                </option>
                                                <option value="student" @if('student'===$user->role)selected @endif>
                                                    Student
                                                </option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <button type="button" class="d-none" id="modalImageTrigger" data-toggle="modal"
                                data-target="#imagemodal">Open Modal
                        </button>
                        <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <img src="none" id="imagePreview" class="w-100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function fullImage(element) {
            let src = element.src;
            document.getElementById('imagePreview').src = src;
            document.getElementById('modalImageTrigger').click()
        }

        function roleChange(element) {
            let id = element.id;
            id = id.replace('user-','');
            document.getElementById('roleChange' + id).submit();
        }
    </script>
@endpush
