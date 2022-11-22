@extends('layouts.app')
@php
    $inputs = [
        [
            'name' => 'username',
            'type'=> 'text',
            'icon' =>'fa-solid fa-user-circle',
            'placeholder' => __('auth.username'),
            'required' => true,
            'autocomplete' => 'off'
        ],
        [
            'name' => 'password',
            'type' => 'password',
            'icon' => 'fa-solid fa-key',
            'placeholder' => __('auth.password'),
            'required' => true,
            'autocomplete' => 'off',
        ]
    ]
@endphp

@section('content')
    <v-container fluid class="login light1 fill-height justify-center" >
        <card-top class="go-down half">

            <h6 class="text-h6 mx-3 text-center accent--text">{{ __('auth.login_welcome', ['attribute' => config('app.name', 'Laravel')])}}</h6>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                @foreach ( $inputs as $input)
                    <icon-input
                    key="{{$input['name']}}"
                    name="{{$input['name']}}"
                    icon="{{$input['icon']}} "
                    type="{{$input['type']}}"
                    placeholder="{{$input['placeholder']}} "
                    required="{{$input['required']}}"
                    autocomplete="{{$input['autocomplete']}}"
                    :reset="@if ($_SERVER["REQUEST_METHOD"] == "POST") true @else false @endif"
                    >
                    @error($input['name'])
                        <template v-slot:error>
                            <p>{{ $message }}</p>
                        </template>
                    @enderror
                    </icon-input>
                @endforeach
                <div class="d-flex justify-center my-3">
                    <btn-pressed type="submit">{{ __('auth.login') }}</btn-pressed>
                </div>

            </form>
        </card-top>
    </v-container>

@endsection
