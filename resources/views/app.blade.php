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
        @keyframes moving {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        .moving-text-container {
            overflow: hidden;
            width: 100%;
        }

        .moving-text {
            white-space: nowrap;
            top: 0;
         }
    </style>
@endpush
@section('content')
    <section class="py-3 py-md-5 py-xl-8" style="background-color: #ffcb03ab;">
             <div id="app">
                <example-component />
            </div>

    </section>
    <div class="moving-text-container bg-black">
        <h5 class="text-white moving-text">
            <span class="p-3">Date of last update: {{$time_stamp??'Not Available'}}</span>
            Date of next update: {{$time_stamp_next??'Not Available'}}
        </h5>
    </div>
    <footer class="footer mt-auto py-3" style="background: rgba(0,166,90,0.54)">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-md-3">
                    <p class="text-white font-weight-bold">© 2024 copyright Plus 94 Research . All rights reserved.</p>
                    <img src="{{asset('images/img_1.png')}}" alt="" width="65" height="65">

                </div>
                <div class="col">
                    <p class="text-white font-weight-bold">Disclaimer: This dashboard is exclusively for client’s use and authorised client teams. Results reflected may vary throughout as the sample grows towards a final target of 1500. Client is advised not to make any final decisions until a sample of at least 1000 respondents is reached.
                        Final datasets are subject to weighting to adjust for sample skews in relation to the population of registered voters.

                    </p>

                 </div>
            </div>
        </div>
    </footer>

@endsection
