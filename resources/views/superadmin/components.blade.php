@extends('layouts.page')

@section('content')

<crud-table
    request="{{request()->route()->uri}}"
    :headers=" {{ Js::from( [
            [ 'text' => '#', 'value' => 'rank'],
            [ 'text' => trans_choice('site.themes', 1), 'value' => 'theme'],
            [ 'text' => trans_choice('site.components', 1), 'value' => 'name'],
            [ 'text' => trans_choice('site.actions', 2), 'value' => 'actions', 'sortable' => false]
        ]
    ) }}"
    :items="{{ Js::from($data) }}"
    :default-item=" {{ Js::from( [
        'theme' => '',
        'name' => ''
    ]) }} "
    :labels="{{ Js::from( [
        'search' => __('site.search'),
        'cancel' => __('site.cancel'),
        'noResultText' => __('site.no result text'),
        'noDataText' => __('site.no_data_text'),
        'itemsPage' => __('site.items_per_p'),
        'newItem' => trans_choice('site.new i', 1) . " " . trans_choice('site.components', 1),
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
    :fields="{{ Js::from( [
        [ 'name' => 'theme', 'label' => trans_choice('site.themes', 1), 'counter' => 50, 'rules' => ['required', 'max-counter'], 'required' => true, 'md' => '6' ],
        [ 'name' => 'name', 'label' => trans_choice('site.components', 1), 'counter' => 50, 'rules' => ['required', 'max-counter'], 'required' => true, 'md' => '6' ],
    ]) }}"
>
</crud-table>


@endsection

