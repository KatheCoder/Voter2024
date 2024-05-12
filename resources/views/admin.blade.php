@extends('layouts.base')

@section('content')
    <div class="container">
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

        <div class="card">
            <div class="card-header">
                Upload CSV Document
            </div>
            <div class="card-body">
                <form action="{{ url('/import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">Choose File:</label>
                        <input class="form-control" type="file" id="file" name="file">
                    </div>
                    <div class="mb-3">
                        <label for="next_update" class="form-label">Next Update:</label>
                        <input class="form-control" required type="text" id="next_update" name="next_update">
                    </div>
                    <button type="submit" class="btn btn-primary">Import</button>
                </form>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ url('/dashboard/A') }}" class="btn btn-info">Go to Dashboard--></a>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script>
        $(document).ready(function(){
            $('#next_update').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });
    </script>
@endpush
