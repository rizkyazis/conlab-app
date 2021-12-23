@extends('layouts.app')

@push('css')
    <style>
        .container-fluid {
            padding: 0;
        }

        .ui-w-80 {
            width: 80px !important;
            height: auto;
        }

        label.btn {
            margin-bottom: 0;
        }

        .text-light {
            color: #babbbc !important;
        }

        .btn-facebook {
            border-color: rgba(0, 0, 0, 0);
            background: #3B5998;
            color: #fff;
        }

        .btn-instagram {
            border-color: rgba(0, 0, 0, 0);
            background: #000;
            color: #fff;
        }

        .card {
            background-clip: padding-box;
            box-shadow: 0 1px 4px rgba(24, 28, 33, 0.012);
        }

        .row-bordered {
            overflow: hidden;
        }

        .account-settings-fileinput {
            position: absolute;
            visibility: hidden;
            width: 1px;
            height: 1px;
            opacity: 0;
        }

        .account-settings-links .list-group-item.active {
            font-weight: bold !important;
        }

        html:not(.dark-style) .account-settings-links .list-group-item.active {
            color: white !important;
        }

        .account-settings-multiselect ~ .select2-container {
            width: 100% !important;
        }

        .light-style .account-settings-links .list-group-item {
            padding: 0.85rem 1.5rem;
            border-color: rgba(24, 28, 33, 0.03) !important;
        }

        .light-style .account-settings-links .list-group-item.active {
            color: #4e5155 !important;
        }

        .material-style .account-settings-links .list-group-item {
            padding: 0.85rem 1.5rem;
            border-color: rgba(24, 28, 33, 0.03) !important;
        }

        .material-style .account-settings-links .list-group-item.active {
            color: #4e5155 !important;
        }

        .dark-style .account-settings-links .list-group-item {
            padding: 0.85rem 1.5rem;
            border-color: rgba(255, 255, 255, 0.03) !important;
        }

        .dark-style .account-settings-links .list-group-item.active {
            color: #fff !important;
        }

        .light-style .account-settings-links .list-group-item.active {
            color: #4E5155 !important;
        }

        .light-style .account-settings-links .list-group-item {
            padding: 0.85rem 1.5rem;
            border-color: rgba(24, 28, 33, 0.03) !important;
        }

        .input-group-text {
            background-color: #FFF;
        }

        .input-group .input-group-text + .form-control {
            border-left: none;
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid min-vh-100 d-flex flex-column">
        <div class="row w-100 flex-grow-1 bg-white">
            <div class="col">
                <div class="row justify-content-center align-items-center h-100">
                    <img src="{{ asset('images/profile-people.svg') }}">
                </div>
            </div>
            <div class="col my-auto">
                <div class="card overflow-hidden">
                    <div class="row no-gutters row-bordered row-border-light">
                        <div class="col-md-3 pt-0">
                            <div class="list-group list-group-flush account-settings-links">
                                <a class="list-group-item list-group-item-action active" data-toggle="list"
                                   href="#account-general">Edit Profile</a>
                                <a class="list-group-item list-group-item-action" data-toggle="list"
                                   href="#account-change-password">Change Password</a>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="account-general">
                                    <form action="{{ route('profile.account.update') }}" method="post"
                                          enctype="multipart/form-data">
                                        <div class="card-body media align-items-center">
                                            <img id="photo"
                                                src="{{ $account->photo ? Storage::url($account->photo) : asset('images/profile-pict.png') }}"
                                                alt="profile_pic" class="d-block ui-w-80">
                                            <div class="media-body ml-4">
                                                <input type="file" name="photo" id="file_upload" hidden>
                                                <button type="button" id="button_upload" class="btn btn-outline-primary">Unggah foto baru
                                                </button>
                                                <div class="text-light small mt-1">Format file harus JPG, JPEG atau PNG.
                                                    Dan ukuran
                                                    file maks 2 MB
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="border-light m-0">
                                        <div class="card-body">
                                            @csrf
                                            <div class="form-group">
                                                <label class="form-label">Fullname</label>
                                                <input type="text"
                                                       class="form-control mb-1 @error('fullname') is-invalid @enderror"
                                                       name="fullname" value="{{ $account->fullname }}">

                                                @error('fullname')
                                                <small class="invalid-feedback">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col">
                                                    <label class="form-label">Birth Date</label>
                                                    <input type="date"
                                                           class="form-control mb-1 @error('birth_date') is-invalid @enderror"
                                                           name="birth_date" value="{{ $account->birth_date }}">

                                                    @error('birth_date')
                                                    <small class="invalid-feedback">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="form-group col">
                                                    <label class="form-label">Birth Place</label>
                                                    <input type="text"
                                                           class="form-control mb-1 @error('birth_place') is-invalid @enderror"
                                                           name="birth_place" value="{{ $account->birth_place }}">

                                                    @error('birth_place')
                                                    <small class="invalid-feedback">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Contact Number</label>
                                                <input type="text"
                                                       class="form-control mb-1 @error('contact') is-invalid @enderror"
                                                       name="contact" value="{{ $account->contact }}">

                                                @error('contact')
                                                <small class="invalid-feedback">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Address</label>
                                                <textarea
                                                    class="form-control mb-1 @error('address') is-invalid @enderror"
                                                    name="address">{{ $account->address }}</textarea>

                                                @error('address')
                                                <small class="invalid-feedback">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">E-mail</label>
                                                <input type="email"
                                                       class="form-control mb-1 @error('email') is-invalid @enderror"
                                                       name="email" maxlength="254" disabled
                                                       value="{{ $account->user->email }}">
                                                @error('email')
                                                <small class="invalid-feedback">{{ $message }}</small>
                                                @enderror
                                                @if(empty($account->user->email_verified_at))
                                                    <div class="alert alert-warning mt-3">
                                                        Your email is not confirmed. Please check your inbox.<br>
                                                        <a href="/email/verify">Resend confirmation</a>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="text-right mt-3">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="account-change-password">
                                    <div class="card-body pb-2">
                                        <form action="{{ route('profile.password.update') }}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <label class="form-label" for="current_password">Old Password</label>
                                                <input type="password" name="old_password"
                                                       class="form-control @error('old_password') is-invalid @enderror"
                                                       id="old_password"
                                                       minlength="8" maxlength="254" required>
                                                @error('old_password')
                                                <small class="invalid-feedback">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="password">Password baru</label>
                                                <input type="password" name="password"
                                                       class="form-control @error('password') is-invalid @enderror"
                                                       minlength="8"
                                                       id="password"
                                                       maxlength="254" required>
                                                @error('password')
                                                <small class="invalid-feedback">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label" for="password_confirmation">Konfirmasi
                                                    password</label>
                                                <input type="password" name="password_confirmation"
                                                       id="password_confirmation"
                                                       class="form-control"
                                                       minlength="8" maxlength="254" required>
                                            </div>
                                            <div class="text-right mt-3">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
        $(document).ready(function () {
            $('#button_upload').on('click', (e) => {
                e.preventDefault();
                $('#file_upload').trigger('click');
            })

            $('#file_upload').on('change', (e) => {
                let file = e.target.files[0];
                let reader = new FileReader();

                reader.onload = () => {
                    $('#photo').attr('src', reader.result);
                }

                reader.readAsDataURL(file);
            });
        })
    </script>
@endpush
