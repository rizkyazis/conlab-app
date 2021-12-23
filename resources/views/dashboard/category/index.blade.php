@extends('layouts.dash')

@push('css')
    <style>
        .logo-category {
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
                                <h3>List Category</h3>
                                <form class="float-right"
                                      action="{{route('category.search')}}"
                                      method="get">
                                    <input type="text" class="form-control" name="name" id="name"
                                           placeholder="Search Category" data-placement="left">
                                </form>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" id="modal-create" data-toggle="modal" data-target="#exampleModal">
                            New
                        </button>
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{route('category.create')}}" method="post" enctype="multipart/form-data">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Create new Category</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @csrf
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" id="name-category" class="form-control" placeholder="Python"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label for="image">Image</label>
                                                <input type="file" name="img" class="form-control" required>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                            </button>
                                            <button type="submit" name="save-category" id="save-category" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-borderless">
                            <thead class="border-bottom">
                            <tr>
                                <th width="10%">No.</th>
                                <th>Category</th>
                                <th>Logo</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$category->name}}</td>
                                    <td><img src="{{Storage::url($category->image)}}" alt="logo" class="logo-category"></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function () {

        })
    </script>
@endpush
