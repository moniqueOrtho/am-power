@extends('layouts.app')

@section('content')
    <v-container fluid class="login light1 fill-height justify-center" >
        <card-top class="go-down half">
            <h6 class="text-h6 mx-3 text-center accent--text">{{ __('site.login_welcome', ['attribute' => config('app.name', 'Laravel')])}}</h6>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <input type="hidden" id="email" name="email" required >
                <icon-input
                key="email"
                name="email"
                icon="fa-solid fa-user-circle"
                type="email"
                placeholder="{{__('site.email')}}"
                :required="true"
                autocomplete="email"
                :reset="false"
                error="@if ($errors->has('email')) {{ $errors->first('email')}} @endif"
                old-value="{{ old('email' )}}"
                >
                </icon-input>

                <input type="hidden" id="password" name="password" required max="15" min="8">
                <icon-input
                key="password"
                name="password"
                icon="fa-solid fa-key"
                type="password"
                placeholder="{{__('site.password')}}"
                :required="true"
                autocomplete="current-password"
                :reset="false"
                :min="8"
                :max="15"
                error="@if ($errors->has('password')) {{ $errors->first('password')}} @endif"
                >
                </icon-input>


                <div class="d-flex justify-center my-3">
                    <btn-pressed type="submit">{{ __('site.login') }}</btn-pressed>
                </div>

            </form>
        </card-top>
    </v-container>

@endsection
