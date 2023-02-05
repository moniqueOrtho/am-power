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
            'subtitle' => trans_choice('site.titles', 1),
            'noSubtitle' => __('site.no_subtitle'),
            'save' => __('site.save'),
            'sectionSaved' => __('site.section_saved'),
            'sectionNotSaved'=> __('site.section_not_saved'),
            'happyCustomers' => __('site.happy_customers'),
            'bestDecision' => __('site.best_decision'),
            'moreReviews' => __('site.more_reviews'),
            'text' => trans_choice('site.texts', 1),
            'button' => trans_choice('site.buttons', 1),
            'layout' => __('site.layout'),
            'new2' => trans_choice('site.new i', 2),
            'closed' => __('site.closed'),
            'editImage'=> __('site.edit_image'),
            'ownImages' => trans_choice('site.own_images', 2),
            'addImage'=> __('site.add_image'),
            'otherImage'=> __('site.other_image'),
            'description' => trans_choice('site.descriptions', 1),
            'close' => __('site.close')
        ])}}"
    >

    </component>

@endsection




