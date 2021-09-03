<aside class="main-sidebar sidebar-light-purple elevation-1">

    <div class="sidebar">

        <div class="my-3 pb-3 d-flex">
            <a href="{{ route('dashboard') }}" class="brand-link border-0">
                <img src="{{ asset('storage/logo.png') }}" alt="logo" class="brand-text d-block mx-auto my-0" style="max-width: 50%;">
            </a>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                @include('layouts.sidemenu.general')
                @include('layouts.sidemenu.users')
                @include('layouts.sidemenu.settings')

            </ul>
        </nav>
    </div>
</aside>