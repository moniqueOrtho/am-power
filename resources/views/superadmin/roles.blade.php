@extends('layouts.page')

@section('content')

<div class="my-8">
    <div class="am-container">
        <crud-table
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
            {{-- :fields="{{ Js::from( [
                [ 'name' => 'theme', 'label' => trans_choice('site.themes', 1), 'counter' => 20, 'rules' => ['required', 'max-counter'], 'required' => true ],
                [ 'name' => 'name', 'label' => trans_choice('site.names', 1) ],
                [ 'name' => 'slug', 'label' => 'url', 'rules' => ['required', 'url'], 'required' => true ],
                [ 'name' => 'lang', 'label' => trans_choice('site.lang', 1), 'input' => 'select', 'rules' => 'required', 'items' => [['text' => 'EN', 'value' => 'en'], ['text' => 'NL', 'value' => 'nl']] ],
                [ 'name' => 'owner_id', 'label' => trans_choice('site.admin', 1), 'input' => 'select', 'rules' => 'required', 'items' => $admins ]
            ]) }}" --}}
            :expand="{{ Js::from([
                'itemKey' => 'role'
            ]) }}"
        >
        </crud-table>
    </div>

</div>


@endsection

