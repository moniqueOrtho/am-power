@extends('layouts.edit')

@section('content')

    <component
        is="{{$component}}"
        :page="{{ Js::from($page) }}"
        :labels="{{ Js::from([
            'feature' => trans_choice('site.features', 1),
            'add' => __('site.add'),
            'title' => trans_choice('site.titles', 1),
            'noTitle' => __('site.no_title'),
            'save' => __('site.save'),
            'sectionSaved' => __('site.section_saved'),
            'sectionNotSaved'=> __('site.section_not_saved'),
            'happyCustomers' => __('site.happy_customers'),
            'bestDecision' => __('site.best_decision')
        ])}}"
    >

    </component>

@endsection




