@extends('layouts.page')

@section('content')
    <div class="my-8">
        <div class="am-container">
            <crud-table
                language="{{ app()->getLocale() }}"
                :headers=" {{ Js::from( [
                        [ 'text' => 'Id', 'value' => 'id'],
                        [ 'text' => 'Name', 'value' => 'name'],
                        [ 'text' => 'Email', 'value' => 'email' ],
                        [ 'text' => 'Role', 'value' => 'role'],
                        [ 'text' => 'Actions', 'value' => 'actions', 'sortable' => false]
                    ]
                ) }}"
                :items="{{ Js::from($data) }}"
                :form-items="{{ Js::from( [
                        [
                            [
                                'name' => 'gender', 'tab' => 1, 'placeholder'=> 'Aanhef', 'sm'=> 4, 'md'=> 2, 'item'=> 'select', 'items'=> [ ['text'=> 'Man', 'value' => 'male' ] , [ 'text' => 'Vrouw', 'value' => 'female' ] ], 'counter'=> false, 'rules'=> '[v => !!v || Aanhef is verplicht ]', 'required' => true
                            ],
                            [
                                'name'=> 'first_name', 'tab'=> 2, 'placeholder'=> 'Voornaam', 'sm'=> 8, 'md'=> 5, 'item'=> 'text', 'icon' => 'mdi-account-circle', 'type' => 'text', 'counter'=> 15, 'rules'=> '[v => (v && v.length <= 15) || De voornaam mag maximaal uit 15 karakters bestaan!]'
                            ],
                            [
                                'name'=> 'last_name', 'tab'=> 3, 'placeholder'=> 'Achternaam', 'md'=> 5, 'item'=> 'text', 'icon' => 'mdi-account-supervisor-circle', 'type' => 'text', 'counter'=> 30, 'rules'=> '[v => !!v || Achternaam is verplicht, v => (v && v.length <= 30) || Achternaam mag maar uit 30 karakters bestaan]', 'required' => true
                            ]
                        ]
                    ]
                ) }} "

                succesMessage=""
            >
            </crud-table>
        </div>

    </div>







    {{-- <h5 class="text-h5">Dit is de gebruikerspagina</h5> --}}
    {{-- @php
    die(json_encode($data) );
    @endphp --}}

@endsection




