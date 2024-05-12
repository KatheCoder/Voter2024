@extends('layouts.base')
@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
            upload csv document
            <form action="{{ url('/import') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file">
                <button type="submit">Import</button>
            </form>
    <a href="{{url('/dashboard/A')}}">see what you uploaded</a>

@endsection
