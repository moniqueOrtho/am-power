@extends('layouts.edit')

@section('content')

<crud-table
    request="{{'/' . strtolower(trans_choice('site.products', 2))}}"
    :crud="{{ Js::from([
        'create' => auth()->user()->hasPermissionTo('create_product'),
        'update' => auth()->user()->hasPermissionTo('update_product'),
        'delete' => auth()->user()->hasPermissionTo('delete_product'),
    ])}}"
    :headers=" {{ Js::from( [
            [ 'text' => '#', 'value' => 'rank'],
            [ 'text' => '', 'value' => 'data-table-expand'],
            [ 'text' => trans_choice('site.names', 1), 'value' => 'name'],
            [ 'text' => trans_choice('site.titles', 1), 'value' => 'title' ],
            [ 'text' => 'Type', 'value' => 'type'],
            [ 'text' => __('site.stock'), 'value' => 'stock' ],
            [ 'text' => trans_choice('site.prices', 1), 'value' => 'price' ],
            [ 'text' => __('site.vat'), 'value' => 'VAT' ],
            [ 'text' => trans_choice('site.actions', 2), 'value' => 'actions', 'sortable' => false, 'align' => 'center']
        ]
    ) }}"
    :items="{{ Js::from($data) }}"
    :default-item=" {{ Js::from( [
        'name' => '',
        'title' => '',
        'type' => 'real',
        'stock' => '',
        'price' => '',
        'VAT' => '21',
        'description' => ''
    ]) }} "
    :fields="{{ Js::from( [
        [ 'name' => 'name', 'label' => trans_choice('site.names', 1), 'counter' => 50, 'rules' => ['required', 'max-counter'], 'required' => true, 'sm' => 6, 'md' => 6 ],
        [ 'name' => 'title', 'label' => trans_choice('site.titles', 1), 'rules' => 'required', 'required' => true, 'sm' => 6, 'md' => 6 ],
        [ 'name' => 'type', 'label' => 'Type', 'input' => 'select', 'items' => [[ 'text' => __('site.digital') . ' ' . strtolower(trans_choice('site.products', 1)), 'value' => 'online'], [ 'text' => __('site.physical') . ' ' . strtolower(trans_choice('site.products', 1)), 'value' => 'real'] ], 'required' => true, 'sm' => 6, 'md' => 6 ],
        [ 'name' => 'stock', 'label' => __('site.stock'), 'type' => 'number', 'min' => 0, 'sm' => 6, 'md' => 6 ],
        [ 'name' => 'price', 'label' => trans_choice('site.prices', 1), 'type' => 'number', 'min' => 0, 'rules' => 'required', 'required' => true, 'sm' => 6, 'md' => 6],
        [ 'name' => 'VAT', 'label' => __('site.vat'), 'input' => 'select', 'items' => [[ 'text' => '0%-tarief', 'value' => '0'], [ 'text' => '9%-tarief', 'value' => '9'], [ 'text' => '21%-tarief', 'value' => '21']  ], 'rules' => 'required', 'required' => true, 'sm' => 6, 'md' => 6],
        [ 'name' => 'description', 'label' => trans_choice('site.descriptions', 1), 'sm' => 12, 'md' => 12, 'input' => 'textarea', 'rows' => 4 ]
    ]) }}"
    :labels="{{ Js::from( [
        'search' => __('site.search'),
        'cancel' => __('site.cancel'),
        'noResultText' => __('site.no result text'),
        'noDataText' => __('site.no_data_text'),
        'itesmPage' => __('site.items_per_p'),
        'newItem' => trans_choice('site.new i', 1) . " " . trans_choice('site.products', 1),
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




