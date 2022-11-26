@php
    $navs = collect(
        [
            [
                'name' => trans_choice('site.pages', 2),
                // 'link' => route('pages')
            ],
            [
                'name' => trans_choice('site.blogs', 2),
                // 'link' => route('pages')
            ],
            [
                'name' => trans_choice('site.sites', 2),
                // 'link' => route('pages')
            ]
        ]
    );
@endphp
<div class="navigation">
    <input type="checkbox" class="navigation__checkbox" id="navi-toggle">
    <label for="navi-toggle" class="navigation__button">
        <span class="navigation__icon">&nbsp;</span>
    </label>
    <div class="navigation__background">&nbsp;</div>
    <nav class="navigation__nav">
        <ul class="navigation__list">
            @foreach ( $navs as $nav)
                <li class="navigation__item"><a href="#" class="navigation__link">{{$nav['name']}}</a></li>
            @endforeach
        </ul>
    </nav>
</div>
