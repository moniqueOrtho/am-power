@extends('layouts.edit')

@section('content')

    <component
        is="{{$component}}"
        :page="{{ Js::from($page) }}"
    >

    </component>

@endsection




