@extends('layouts.base')
@push('head')
    <style>
        body {
            background-color: #006600; /* ANC green */
            background-image: url('{{ asset('images/anc.png') }}'); /* ANC flag */
            background-size: cover;
            background-position: center;
            color: #fff;
            display: flex;
            align-items: center;

        }
    </style>
@endpush
@section('content')
    <section class="py-3 py-md-5 py-xl-8" style="background-color: #ffcb03ab;">
             <div id="app">
                <example-component />
            </div>

    </section>
@endsection
