@php
    use Illuminate\Support\Facades\Route;
    $navs = collect(
        [
            [
                'name' => 'users',
                'perm'  => 'user',
                'label' => trans_choice('site.users', 2),
                'link' => route('users')
            ],
            [
                'name' => 'sites',
                'perm'  => 'site',
                'label' => trans_choice('site.sites', 2),
                'link' => route('sites')
            ],
            [
                'name' => 'roles',
                'perm'  => 'role',
                'label' => trans_choice('site.roles', 2),
                'link' => route('roles')
            ],
            [
                'name' => 'permissions',
                'perm'  => 'permission',
                'label' => trans_choice('site.permissions', 2),
                'link' => route('permissions')
            ],
            [
                'name' => 'components',
                'perm'  => 'component',
                'label' => trans_choice('site.components', 2),
                'link' => route('components')
            ],
            [
                'name' => 'pages',
                'perm'  => 'page',
                'label' => trans_choice('site.pages', 2),
                'link' => '#'
            ],
            [
                'name' => 'posts',
                'perm'  => 'post',
                'label' => trans_choice('site.posts', 2),
                'link' => '#'
            ],
        ]
    );
    $perms = auth()->user()->permissions;
    $route = Route::currentRouteName();
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
                        @if ( $perms->contains("update_" . $nav['perm']) )
                            <div class="g-col-12 g-col-md-6 g-col-lg-4">
                                <div class="navigation__item navigation__item--nav @if ($nav['name'] === $route)
                                active @endif"><a href="{{$nav['link']}}" class="navigation__link">{{$nav['label']}}</a>
                            </div>
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
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="navigation__font"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M160 96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96C43 32 0 75 0 128V384c0 53 43 96 96 96h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H96c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32h64zM504.5 273.4c4.8-4.5 7.5-10.8 7.5-17.4s-2.7-12.9-7.5-17.4l-144-136c-7-6.6-17.2-8.4-26-4.6s-14.5 12.5-14.5 22v72H192c-17.7 0-32 14.3-32 32l0 64c0 17.7 14.3 32 32 32H320v72c0 9.6 5.7 18.2 14.5 22s19 2 26-4.6l144-136z"/>
                </svg>
            </button>
        </form>

    </nav>
</div>
@section('js')
   <script>window.addEventListener("load", () => {
    document.getElementById('navi-toggle').checked = true;
   });</script>
@endsection
