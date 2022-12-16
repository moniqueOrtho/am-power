@extends('layouts.page')

@section('content')
    <div class="my-8">
        <div class="am-container">
            <crud-table
                request="{{request()->route()->uri}}"
                :headers=" {{ Js::from( [
                        [ 'text' => 'Id', 'value' => 'id'],
                        [ 'text' => __('site.gender'), 'value' => 'gender'],
                        [ 'text' => trans_choice('site.names', 1), 'value' => 'name'],
                        [ 'text' => trans_choice('site.emails', 1), 'value' => 'email' ],
                        [ 'text' => trans_choice('site.roles', 1), 'value' => 'role'],
                        [ 'text' => trans_choice('site.actions', 2), 'value' => 'actions', 'sortable' => false]
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
                :labels="{{ Js::from( [
                    'search' => __('site.search'),
                    'cancel' => __('site.cancel'),
                    'noResultText' => __('site.no result text'),
                    'itemsPage' => __('site.items_per_p'),
                    'newItem' => trans_choice('site.new i', 2) . " " . trans_choice('site.users', 1),
                    'editItem' => __('site.edit'),
                    'save' => __('site.save'),
                    'succesMessage' => __('site.succes_message'),
                    'update_message' => __('site.update_message'),
                    'required' => __('site.is_required'),
                    'emailInvalid' => __('site.email_invalid'),
                    'maxCounter' => __('site.error_count_vue')
                ]) }}"
                :fields="{{ Js::from( [
                    [ 'name' => 'gender', 'label' => __('site.gender'), 'input' => 'select', 'rules' => 'required', 'items' => [ [ 'text' => __('site.male'), 'value' => 'male'], [ 'text' => __('site.female'), 'value' => 'female']] ],
                    [ 'name' => 'first_name', 'label' => __('site.first_name') ],
                    [ 'name' => 'last_name', 'label' => __('site.last_name'), 'counter' => 20, 'rules' => ['required', 'max-counter'], 'required' => true ],
                    [ 'name' => 'email', 'label' => trans_choice('site.emails', 1), 'type'=> 'email', 'rules' => ['required', 'email'], 'required' => true ],
                    [ 'name' => 'role_id', 'label' => trans_choice('site.roles', 1), 'input' => 'select', 'rules' => 'required', 'items' => $roles ]
                ]) }}"
            >
            </crud-table>
        </div>

    </div>







    {{-- <h5 class="text-h5">Dit is de gebruikerspagina</h5> --}}
    {{-- @php
    die( Js::from($data));
    @endphp --}}

@endsection




