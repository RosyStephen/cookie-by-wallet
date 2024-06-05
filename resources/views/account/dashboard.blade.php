@extends('layouts.app')
@section('main')
<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('layouts.sidebar')
        </div>
        <div class="col-md-8 ">
            @include('layouts.message')

            <div class="card border-0 shadow">
                <div class="card-header  text-white">
                    Dashboard
                </div>
                <div class="dashboard-header row">
                    <div class="col-md-3 my-4 ms-4">
                        <div class="card border-0 shadow-lg">
                            <div class="card-header  text-white">
                                Current Balance
                            </div>
                            <div class="card-body">

                                <div class="h5 text-center">
                                    <strong>{{ format_money(Auth::user()->wallet) }}</strong>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 my-4 ms-4">
                        <div class="card border-0 shadow-lg">
                            <div class="card-header  text-white">
                                Previous Balance
                            </div>
                            <div class="card-body">

                                <div class="h5 text-center">
                                    <strong>{{ format_money(Auth::user()->old_balance) }}</strong>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 my-4 ms-4">
                        <div class="card border-0 shadow-lg">
                            <div class="card-header  text-white" style=" {{ Auth::user()->subscribed ? 'background:green' : 'background:red'}} ">
                                Subscription
                            </div>
                            <div class="card-body">

                                <div class="h5 text-center" style=" {{ Auth::user()->subscribed ? 'color:green' : 'color:red'}} ">
                                    <strong> 
                                        @if(Auth::user()->subscribed )
                                            ✔
                                        @else
                                            <a href="#">subscribe</a>

                                        @endif
                                    </strong>

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