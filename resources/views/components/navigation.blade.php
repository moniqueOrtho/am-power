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
    <label for="navi-toggle" class="navigation__button navigation__button--menu">
        <span class="navigation__icon">&nbsp;</span>
    </label>
    <div class="navigation__background">&nbsp;</div>
    <nav class="navigation__nav">
        <div class="navigation__list">
            <div class="container">
                <div class="grid">
                    @foreach ( $navs as $nav)
                        @if ( $perms->contains("update_" . $nav['name']) )
                            <div class="g-col-12 g-col-md-6 g-col-lg-4">
                                <div class="navigation__item navigation__item--nav"><a href="#" class="navigation__link">{{$nav['label']}}</a></div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        {{-- <form action="{{route('logout')}}" method="POST">
            @csrf
            <btn-pressed >{{ __('site.logout') }}</btn-pressed>
        </form> --}}
        <form action="{{route('logout')}}" method="POST">
            @csrf
            <button class="navigation__button navigation__button--out navigation__item" type="submit">
                <i class="fa-solid fa-right-from-bracket navigation__font"></i>
            </button>
        </form>

    </nav>
</div>
