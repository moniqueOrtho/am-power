@php
    use Illuminate\Support\Facades\Route;

    $sites = auth()->user()->ownedSites;

    $navs = collect(
        [
            [
                'name' => 'admins',
                'perm'  => 'admin',
                'label' => trans_choice('site.admin', 2),
                'link' => route('users')
            ],
            [
                'name' => 'subscriber',
                'perm'  => 'subscriber',
                'label' => trans_choice('site.subscribers', 2),
                'link' => route('subscribers', ['siteId' => $sites->isNotEmpty() ? $sites->first() : 0])
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
                'name' => 'pages',
                'perm'  => 'page',
                'label' => trans_choice('site.pages', 2),
                'link' => route('pages', ['siteId' => $sites->isNotEmpty() ? $sites->first() : 0])
            ],
            [
                'name' => 'products',
                'perm'  => 'product',
                'label' => trans_choice('site.products', 2),
                'link' => route('products', ['siteId' => $sites->isNotEmpty() ? $sites->first() : 0])
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
    <div class="navigation__container relative">

            @if ($route != 'home')
                <input type="checkbox" class="navigation__checkbox" id="navi-toggle">
                <label for="navi-toggle" class="navigation__button navigation__button--menu">
                    <span class="navigation__icon
                        @if ($state == 'open')
                            navigation__icon--open
                        @else
                            navigation__icon--closed
                        @endif"
                    >&nbsp;</span>
                </label>
            @endif
            <div class="navigation__background
                @if ($state == 'open')
                    navigation__background--open
                @else
                    navigation__background--closed
                @endif"
            >&nbsp;</div>
            <nav class="navigation__nav">
                <div class="navigation__list">
                    <div class="navigation__row">
                        @foreach ( $navs as $nav)
                            @if ( $perms->contains("update_" . $nav['perm']) )

                                <div class="navigation__item navigation__item--nav @if ($nav['name'] ===    $route)
                                    active @endif"><a href="{{$nav['link']}}" class="navigation__link">{{$nav['label']}}</a>
                                </div>

                            @endif
                        @endforeach
                    </div>
                </div>
                {{-- <form action="{{route('logout')}}" method="POST">
                    @csrf
                    <btn-pressed >{{ __('site.logout') }}</btn-pressed>
                </form> --}}
                {{-- <div class="page-curl page-curl--bottom-right"></div> --}}
                <form action="{{route('logout')}}" method="POST">
                    @csrf

                    <v-tooltip left color="accent">
                        <template v-slot:activator="{ on, attrs }">
                            <button
                                class="navigation__button navigation__button--out navigation__item"
                                type="submit"
                                v-bind="attrs"
                                v-on="on"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="navigation__font"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M160 96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96C43 32 0 75 0 128V384c0 53 43 96 96 96h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H96c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32h64zM504.5 273.4c4.8-4.5 7.5-10.8 7.5-17.4s-2.7-12.9-7.5-17.4l-144-136c-7-6.6-17.2-8.4-26-4.6s-14.5 12.5-14.5 22v72H192c-17.7 0-32 14.3-32 32l0 64c0 17.7 14.3 32 32 32H320v72c0 9.6 5.7 18.2 14.5 22s19 2 26-4.6l144-136z"/>
                                </svg>
                            </button>
                        </template>
                        <span>{{ __('site.logout')  }}</span>
                      </v-tooltip>

                </form>

            </nav>

    </div>

    @if ( $header || $sites->count() <= 1 )
        <div class="am-container light1">
            <h4 class="text-h4 navigation__title mb-0">{{trans_choice('site.' . $route, 2)}}</h4>
        </div>
    @endif

    <div id="overlay">
        <v-overlay z-index="500">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
    </div>
</div>
@section('js')
   <script>
    function hideOverlay() {
        document.getElementById('overlay').classList.add('hidden');
    }
    function showOverlay() {
        document.getElementById('overlay').classList.remove('hidden');
    }
    window.addEventListener("load", () => {
        hideOverlay();
        if(window.location.pathname !== '/home') {
            document.getElementById('navi-toggle').checked = true;
        }
        document.querySelector('.navigation__button--out').addEventListener('click', showOverlay);
        const nodelist = document.querySelectorAll(".navigation__link");
        for (let i = 0; i<nodelist.length; i++) {
            nodelist[i].addEventListener('click', showOverlay);
        }
    });
   </script>
@endsection
