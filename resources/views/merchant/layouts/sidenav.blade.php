<aside class="main-sidebar sidebar-light-purple elevation-1">

    <div class="sidebar">

        <div class="my-3 pb-3 d-flex">
            <a href="{{ route('merchant.dashboard') }}" class="brand-link border-0">
                <img src="{{ asset('storage/logo.png') }}" alt="logo" class="brand-text d-block mx-auto my-0" style="max-width: 50%;">
            </a>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('merchant.dashboard') }}" class="nav-link {{ Nav::hasSegment('dashboard', 1, 'active') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('modules.dashboard') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('merchant.careers.index') }}" class="nav-link {{ Nav::hasSegment('careers', 1, 'active') }}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>{{ trans_choice('modules.career', 2) }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('merchant.branches.index') }}" class="nav-link {{ Nav::hasSegment('branches', 1, 'active') }}">
                        <i class="nav-icon fas fa-store"></i>
                        <p>{{ trans_choice('modules.branch', 2) }}</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>