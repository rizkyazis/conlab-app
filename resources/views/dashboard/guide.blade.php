@extends('layouts.dash')

@push('css')
@endpush

@section('content')
    <div class="container mt-3">
        @if($account->role == 'reviewer')
            @if($account->role == 'teacher')
                <p>guide guru</p>
                @if($account->role == 'teacher')
                    <p>guide admin</p>
                @endif
            @endif
                <p>Guide reviewer</p>
        @endif
    </div>
@endsection
@push('script')
@endpush
