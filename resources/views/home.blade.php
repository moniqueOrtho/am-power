@extends('layouts.app')

@section('content')
<x-navigation/>
<v-container fluid class="accent fill-height justify-center" >
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">

            </div>

            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</v-container>
@endsection
