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
            <form>
                {{-- @php
                   var_dump($inputs[0]['name']);
                @endphp --}}

                @foreach ( $inputs as $input)
                {{-- <p>{{$input['name']}}</p> --}}
                    <icon-input
                    key="{{$input['name']}}"
                    name="{{$input['name']}}"
                    icon="{{$input['icon']}} "
                    type="{{$input['type']}}"
                    placeholder="{{$input['placeholder']}} "
                    required="{{$input['required']}}"
                    :backendError="null"
                    autocomplete="{{$input['autocomplete']}}"

                    :reset="false"
                    ></icon-input>
                @endforeach

            </form>
        </card-top>
    </v-container>

@endsection
