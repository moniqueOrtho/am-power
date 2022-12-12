@extends('layouts.page')

@section('content')
    <div class="my-8">
        <div class="am-container">
            <crud-table
                langauage = {{app()->getLocale()}}
                :headers=" {{ Js::from( [
                        [ 'text' => 'Id', 'value' => 'id'],
                        [ 'text' => 'Name', 'value' => 'name'],
                        [ 'text' => 'Email', 'value' => 'email' ],
                        [ 'text' => 'Role', 'value' => 'role'],
                        [ 'text' => 'Actions', 'value' => 'actions', 'sortable' => false]
                    ]
                ) }}"
                :items="{{ Js::from($data) }}"
                :default-item=" {{ Js::from( [
                    'gender' => '',
                    'first_name' => '',
                    'last_name' => '',
                    'email' => '',
                    'role_id' => ''
                ]) }} "
            >
            </crud-table>
        </div>

    </div>







    {{-- <h5 class="text-h5">Dit is de gebruikerspagina</h5> --}}
    {{-- @php
    die( Js::from($data));
    @endphp --}}

@endsection




