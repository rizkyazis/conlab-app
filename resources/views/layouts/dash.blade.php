<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Conlab Dashboard</title>
    <link rel="icon" type="image/x-icon" href="{{asset('images/logo.ico')}}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        #wrapper {
            overflow-x: hidden;
        }

        #menu-toggle {
            cursor: pointer;
        }

        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -15rem;
            -webkit-transition: margin .25s ease-out;
            -moz-transition: margin .25s ease-out;
            -o-transition: margin .25s ease-out;
            transition: margin .25s ease-out;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
        }

        #sidebar-wrapper .list-group {
            width: 20rem;
        }

        #page-content-wrapper {
            min-width: 100vw;
        }

        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }

        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }

            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }

            #wrapper.toggled #sidebar-wrapper {
                margin-left: -20rem;
            }
        }

         .guide-float{
             height: 80px;
             width: 80px;
             border-radius: 50%;
             position: fixed;
             right: 24px;
             bottom: 24px;
         }

    </style>
    @stack('css')
</head>
<body>
<div id="app">
    @include('sweetalert::alert')
    <div class="d-flex" id="wrapper">

        <!-- Sidebar -->
        <div class="bg-white shadow" id="sidebar-wrapper">
            <div class="sidebar-heading"><a class="navbar-brand" href="{{ route('dashboard') }}"><img
                        src="{{asset('images/logo-blue.svg')}}" alt=""></a></div>
            <div class="list-group list-group-flush">
                <a href="{{ route('dashboard') }}"
                   class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                @if((auth()->user()->role)== 'admin')
                    <a href="{{ route('user.index') }}"
                       id="user-menu"
                       class="list-group-item list-group-item-action {{ request()->routeIs('user.index') ? 'active' : '' }}">User</a>
                    <a href="{{ route('category.index') }}"
                       id="category-menu"
                       class="list-group-item list-group-item-action {{ request()->routeIs('category.index') ? 'active' : '' }}">Category</a>
                @endif
                @if((auth()->user()->role)!= 'reviewer')
                    <a href="{{ route('dashboard.course.index') }}"
                       id="course-menu"
                       class="list-group-item list-group-item-action {{ (request()->routeIs('dashboard.course.index')||request()->routeIs('dashboard.course.search')||request()->routeIs('dashboard.course.new')||request()->routeIs('dashboard.course.detailed')||request()->routeIs('dashboard.course.detailed.new')||request()->routeIs('dashboard.course.new.info')) ? 'active' : '' }}">Courses</a>
                    <a href="{{route('dashboard.quiz.index')}}"
                       id="quiz-menu"
                       class="list-group-item list-group-item-action {{ (request()->routeIs('dashboard.quiz.index')||request()->routeIs('dashboard.quiz.search')||request()->routeIs('dashboard.quiz.section')||request()->routeIs('dashboard.quiz.detail')||request()->routeIs('dashboard.quiz.question')) ? 'active' : '' }}">Quiz</a>
                @endif
                <a href="{{ route('review') }}"
                   id="review-menu"
                   class="list-group-item list-group-item-action {{ (request()->routeIs('review')||request()->routeIs('course.review.search')||request()->routeIs('review.lessons')||request()->routeIs('review.participants')||request()->routeIs('review.participants_search')||request()->routeIs('review.code')) ? 'active' : '' }}">Reviews</a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <nav class="navbar navbar-expand-lg navbar-light bg-primary shadow">
                <i class="fa fa-bars text-white" id="menu-toggle"></i>

                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        @auth
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#"
                                   role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                   v-pre>
                                    {{ auth()->user()->account->fullname ? auth()->user()->account->fullname : auth()->user()->username }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endauth
                    </ul>
                </div>
            </nav>

            <main>
                @yield('content')
            </main>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
</div>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <img src="{{asset('images/logo-blue.svg')}}" alt="" style="height: 36px">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @stack('modal')
            </div>
        </div>
    </div>
</div>
<!-- Scripts -->
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<script>
    $(document).ready(function () {
        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    })
</script>

@stack('script')
</body>
</html>
