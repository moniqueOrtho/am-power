@extends('layouts.app')
@php
    $inputs = [
        [
            'name' => 'email',
            'type'=> 'email',
            'icon' =>'fa-solid fa-user-circle',
            'placeholder' => __('auth.email'),
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
            'min' => 8
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
                    <input type="hidden" id="{{$input['name']}}" name="{{$input['name']}}" required="{{$input['required']}}" value="">

                    <icon-input
                    key="{{$input['name']}}"
                    name="{{$input['name']}}"
                    icon="{{$input['icon']}} "
                    type="{{$input['type']}}"
                    placeholder="{{$input['placeholder']}} "
                    required="{{$input['required']}}"
                    autocomplete="{{$input['autocomplete']}}"
                    :reset="false"
                    min="{{$input['min'] ?? null}}"
                    errorMessage="@error($input['name']) {{$message}} @enderror"
                    >
                    @error($input['name'])
                        <template v-slot:error>

                        </template>
                    @enderror
                    </icon-input>

                @endforeach
                <div class="d-flex justify-center my-3">
                    <btn-pressed type="submit">{{ __('auth.login') }}</btn-pressed>
                </div>

            </form>
            <v-overlay :value="@if (isset($_POST['submit'])) true @else false @endif">
                <v-progress-circular
                  indeterminate
                  size="64"
                ></v-progress-circular>
            </v-overlay>
        </card-top>
    </v-container>

@endsection
