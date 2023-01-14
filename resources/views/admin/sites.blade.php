@extends('layouts.page')

@php
    $headers = [
        [ 'text' => '#', 'value' => 'rank'],
        [ 'text' => trans_choice('site.themes', 1), 'value' => 'theme'],
        [ 'text' => trans_choice('site.names', 1), 'value' => 'name'],
        [ 'text' => 'url', 'value' => 'slug' ],
        [ 'text' => trans_choice('site.lang', 1), 'value' => 'lang', 'align' => 'center', 'sortable' => false],
    ];

    $defaultItem = [
        'theme' => '',
        'name' => '',
        'title' => '',
        'slug' => '',
        'lang' => '',
    ];

    $fields = [
        [ 'name' => 'theme', 'label' => trans_choice('site.themes', 1), 'counter' => 20, 'rules' => ['required', 'max-counter'], 'required' => true ],
        [ 'name' => 'name', 'label' => trans_choice('site.names', 1) ],
        [ 'name' => 'title', 'label' => trans_choice('site.titles', 1) ],
        [ 'name' => 'slug', 'label' => 'url', 'rules' => ['required', 'url'], 'required' => true ],
        [ 'name' => 'lang', 'label' => trans_choice('site.lang', 1), 'input' => 'select', 'rules' => 'required', 'items' => [['text' => 'EN', 'value' => 'en'], ['text' => 'NL', 'value' => 'nl']] ],

    ];

    if(auth()->user()->hasRole('superadmin')) {
        array_push($headers, [ 'text' => trans_choice('site.admin', 1), 'value' => 'owner_name'],
                [ 'text' => trans_choice('site.actions', 2), 'value' => 'actions', 'sortable' => false]
                );
        $defaultItem['owner_id'] = '';
        array_push($fields, [ 'name' => 'owner_id', 'label' => trans_choice('site.admin', 1), 'input' => 'select', 'rules' => 'required', 'items' => $admins ]);
     } else {
        array_push($headers, [ 'text' => trans_choice('site.actions', 2), 'value' => 'actions', 'sortable' => false, 'align' => 'center'] );
    }
@endphp

@section('content')

<crud-table
    request="{{request()->route()->uri}}"
    :headers=" {{ Js::from( $headers ) }}"
    :items="{{ Js::from($data) }}"
    :default-item=" {{ Js::from( $defaultItem ) }} "
    :labels="{{ Js::from( [
        'search' => __('site.search'),
        'cancel' => __('site.cancel'),
        'noResultText' => __('site.no result text'),
        'noDataText' => __('site.no_data_text'),
        'itemsPage' => __('site.items_per_p'),
        'newItem' => trans_choice('site.new i', 2) . " " . trans_choice('site.sites', 1),
        'editItem' => __('site.edit'),
        'save' => __('site.save'),
        'succesMessage' => __('site.succes_message'),
        'updateMessage' => __('site.update_message'),
        'required' => __('site.is_required'),
        'emailInvalid' => __('site.email_invalid'),
        'urlInvalid' => __('site.url_invalid'),
        'maxCounter' => __('site.error_count_vue'),
        'deleteMessage' => __('site.delete_message_vue'),
        'deleteSuccessful' => __('site.delete_successful')
    ]) }}"
    :fields="{{ Js::from( $fields) }}"
    :crud="{{ Js::from([
        'create' => auth()->user()->hasPermissionTo('create_site'),
        'update' => auth()->user()->hasPermissionTo('update_site'),
        'delete' => auth()->user()->hasPermissionTo('delete_site'),
    ])}}"
>
</crud-table>


@endsection

