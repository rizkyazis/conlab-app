@extends('layouts.app')

@push('css')
    <style>
        body{
            height: 100%;
        }

        .has-icon .form-control {
            padding-left: 2.375rem;
        }

        .has-icon .form-control-feedback {
            position: absolute;
            z-index: 2;
            display: block;
            width: 2.375rem;
            height: 2.375rem;
            line-height: 2.375rem;
            text-align: center;
            pointer-events: none;
            color: #aaa;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid min-vh-100 d-flex flex-column">
        <div class="row h-100 flex-grow-1">
            <div class="col bg-primary">
                <div class="row justify-content-center align-items-center h-100">
                    <img src="{{ asset('images/register.svg') }}">
                </div>
            </div>
            <div class="col bg-white">
                <div class="container p-5 h-100">
                    <div class="row justify-content-center align-items-center h-100">
                        <div class="col-8" style="margin-top: -200px">
                            <h1 class="font-weight-bold">Let's Started!</h1>
                            <p class="mt-2">Sudah punya akun? <a href="{{ route('login') }}" class="font-weight-bold">Login</a></p>

                            <form method="POST" class="mt-5" action="{{ route('register') }}">
                                @csrf
                                <div class="form-group has-icon">
                                    <label for="username" class="font-weight-bolder">Username</label>
                                    <span class="fa fa-user form-control-feedback"></span>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username">
                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <span>{{ $message }}</span>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group has-icon">
                                    <label for="email" class="font-weight-bolder">Email Address</label>
                                    <span class="fa fa-envelope-o form-control-feedback"></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <span>{{ $message }}</span>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group has-icon">
                                    <label for="password" class="font-weight-bolder">Password</label>
                                    <span class="fa fa-lock form-control-feedback"></span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <span>{{ $message }}</span>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group has-icon">
                                    <label for="password" class="font-weight-bolder">Password Confirmation</label>
                                    <span class="fa fa-lock form-control-feedback"></span>
                                    <input type="password" class="form-control" id="password" name="password_confirmation">
                                </div>
                                <button class="btn btn-primary btn-block" style="margin-top: 100px">Sign In</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
