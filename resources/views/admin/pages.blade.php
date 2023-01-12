@extends('layouts.page')

@section('content')

<crud-table
    request="{{'/' . __('site.pages_slug')}}"
    :headers=" {{ Js::from( [
            [ 'text' => '#', 'value' => 'rank'],
            [ 'text' => '', 'value' => 'data-table-expand'],
            [ 'text' => trans_choice('site.icons', 1), 'value' => 'icon', 'sortable' => false],
            [ 'text' => trans_choice('site.names', 1), 'value' => 'name'],
            [ 'text' => trans_choice('site.titles', 1), 'value' => 'title' ],
            [ 'text' => 'url', 'value' => 'slug'],
            [ 'text' => trans_choice('site.actions', 2), 'value' => 'actions', 'sortable' => false]
        ]
    ) }}"
    :items="{{ Js::from($data) }}"
    :default-item=" {{ Js::from( [
        'name' => '',
        'title' => '',
        'subtitle' => '',
        'slug' => '',
        'icon' => '',
        'description' => '',
    ]) }} "
    :fields="{{ Js::from( [
        [ 'name' => 'name', 'label' => trans_choice('site.names', 1), 'counter' => 50, 'rules' => ['required', 'max-counter'], 'required' => true, 'sm' => 6, 'md' => 6 ],
        [ 'name' => 'title', 'label' => trans_choice('site.titles', 1), 'rules' => 'required', 'required' => true, 'sm' => 6, 'md' => 6 ],
        [ 'name' => 'subtitle', 'label' => trans_choice('site.subtitles', 1), 'sm' => 12, 'md' => 12 ],
        [ 'name' => 'slug', 'label' => 'url', 'sm' => 6, 'md' => 6 ],
        [ 'name' => 'icon', 'label' => trans_choice('site.icons', 1), 'sm' => 6, 'md' => 6 ],
        [ 'name' => 'description', 'label' => trans_choice('site.descriptions', 1), 'sm' => 12, 'md' => 12, 'input' => 'textarea', 'rows' => 4 ]
    ]) }}"
    :labels="{{ Js::from( [
        'search' => __('site.search'),
        'cancel' => __('site.cancel'),
        'noResultText' => __('site.no result text'),
        'noDataText' => __('site.no_data_text'),
        'itesmPage' => __('site.items_per_p'),
        'newItem' => trans_choice('site.new i', 2) . " " . trans_choice('site.pages', 1),
        'editItem' => __('site.edit'),
        'save' => __('site.save'),
        'succesMessage' => __('site.succes_message'),
        'updateMessage' => __('site.update_message'),
        'required' => __('site.is_required'),
        'emailInvalid' => __('site.email_invalid'),
        'maxCounter' => __('site.error_count_vue'),
        'deleteMessage' => __('site.delete_message_vue'),
        'deleteSuccessful' => __('site.delete_successful')
    ]) }}"
    @if ($sites->count() > 1)
        :table-select="{{Js::from([
            'title' => trans_choice('site.' . Route::currentRouteName(), 2),
            'label' => trans_choice('site.sites', 1),
            'items' => $sites,
            'defaultInput' => (int)$siteId,
            'request' => 'site_id'
        ])}}"
    @endif
>
</crud-table>







    {{-- <h5 class="text-h5">Dit is de gebruikerspagina</h5> --}}
    {{-- @php
    die( Js::from($data));
    @endphp --}}

@endsection




