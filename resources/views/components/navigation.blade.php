@php
    $navs = collect(
        [
            [
                'name' => 'user',
                'label' => trans_choice('site.users', 2)
                // 'link' => route('pages')
            ],
            [
                'name' => 'site',
                'label' => trans_choice('site.sites', 2),
                // 'link' => route('pages')
            ],
            [
                'name' => 'role',
                'label' => trans_choice('site.roles', 2),
                // 'link' => route('pages')
            ],
            [
                'name' => 'permission',
                'label' => trans_choice('site.permissions', 2),
                // 'link' => route('pages')
            ],
            [
                'name' => 'component',
                'label' => trans_choice('site.components', 2),
                // 'link' => route('pages')
            ],
            [
                'name' => 'page',
                'label' => trans_choice('site.pages', 2),
                // 'link' => route('pages')
            ],
            [
                'name' => 'post',
                'label' => trans_choice('site.posts', 2),
                // 'link' => route('pages')
            ],
        ]
    );
    $perms = auth()->user()->permissions;
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
                @if ( $perms->contains("update_" . $nav['name']) )
                    <li class="navigation__item"><a href="#" class="navigation__link">{{$nav['label']}}</a></li>
                @endif
            @endforeach
        </ul>
    </nav>
</div>
