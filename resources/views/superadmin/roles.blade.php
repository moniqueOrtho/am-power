@extends('layouts.page')

@section('content')

<crud-table
    :info="{{ Js::from([
        'editTitleObject' => 'role',
        'succesMessageObject' => 'role',
        'deleteMessageObject'=> 'role'
    ])}}"
    request="{{request()->route()->uri}}"
    :headers=" {{ Js::from( [
            [ 'text' => '#', 'value' => 'rank'],
            [ 'text' => '', 'value' => 'data-table-expand'],
            [ 'text' => trans_choice('site.roles', 1), 'value' => 'role'],
            [ 'text' => trans_choice('site.descriptions', 1), 'value' => 'description'],
            [ 'text' => trans_choice('site.actions', 2), 'value' => 'actions', 'sortable' => false]
        ]
    ) }}"
    :items="{{ Js::from($data) }}"
    :default-item=" {{ Js::from( [
        'role' => '',
        'description' => '',
        'permissions' => [],
    ]) }} "
    :labels="{{ Js::from( [
        'search' => __('site.search'),
        'cancel' => __('site.cancel'),
        'noResultText' => __('site.no result text'),
        'noDataText' => __('site.no_data_text'),
        'itemsPage' => __('site.items_per_p'),
        'newItem' => trans_choice('site.new i', 2) . " " . trans_choice('site.roles', 1),
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
    :fields="{{ Js::from( $perms ) }}"
    :expand="{{ Js::from([
        'itemKey' => 'role'
    ]) }}"
>
</crud-table>


@endsection

