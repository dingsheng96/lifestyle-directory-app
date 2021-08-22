<aside class="main-sidebar sidebar-light-purple elevation-1">

    <div class="sidebar">

        <div class="my-3 pb-3 d-flex">
            <a href="{{ route('dashboard') }}" class="brand-link border-0">
                <img src="{{ asset('storage/logo.png') }}" alt="logo" class="brand-text d-block mx-auto my-0" style="max-width: 50%;">
            </a>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-header">{{ __('modules.general') }}</li>

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Nav::hasSegment('dashboard', 1, 'active') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('modules.dashboard') }}</p>
                    </a>
                </li>

                {{-- USERS --}}
                @canany(['merchant.create', 'merchant.read', 'merchant.update', 'merchant.delete', 'member.create', 'member.read', 'member.update', 'member.delete', 'admin.create', 'admin.read', 'admin.update', 'admin.delete'])

                <li class="nav-header">{{ __('modules.users') }}</li>

                @canany(['merchant.create'])
                {{-- <li class="nav-item">
                    <a href="{{ route('verifications.index') }}" class="nav-link {{ Nav::hasSegment('verifications', 1, 'active') }}">
                <i class="nav-icon fas fa-id-card"></i>
                <p>{{ trans_choice('modules.verification', 2) }}</p>
                <span class="badge badge-pill badge-light px-2 bg-orange right">{{ $verifications_count ?? 0 }}</span>
                </a>
                </li> --}}
                @endcanany

                @canany(['merchant.read', 'merchant.update', 'merchant.delete'])
                <li class="nav-item">
                    <a href="{{ route('merchants.index') }}" class="nav-link {{ Nav::hasSegment('merchants', 1, 'active') }}">
                        <i class="nav-icon fas fa-store"></i>
                        <p>{{ trans_choice('modules.merchant', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @canany(['member.create', 'member.read', 'member.update', 'member.delete'])
                <li class="nav-item">
                    <a href="{{ route('members.index') }}" class="nav-link {{ Nav::hasSegment('members', 1, 'active') }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>{{ trans_choice('modules.member', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @canany(['admin.create', 'admin.read', 'admin.update', 'admin.delete'])
                <li class="nav-item">
                    <a href="{{ route('admins.index') }}" class="nav-link {{ Nav::hasSegment('admins', 1, 'active') }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>{{ trans_choice('modules.admin', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @endcanany
                {{-- END USERS --}}

                {{-- SETTINGS --}}
                @canany(['locale.create', 'locale.read', 'locale.update', 'locale.delete', 'role.create', 'role.read', 'role.update', 'role.delete', 'category.create', 'category.read', 'category.update', 'category.delete'])

                <li class="nav-header">{{ __('modules.settings') }}</li>

                @canany(['role.create', 'role.read', 'role.update', 'role.delete'])
                <li class="nav-item">
                    <a href="{{ route('roles.index') }}" class="nav-link {{ Nav::hasSegment('roles', 1, 'active') }}">
                        <i class="nav-icon fas fa-user-tag"></i>
                        <p>{{ trans_choice('modules.role', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @canany(['category.create', 'category.read', 'category.update', 'category.delete'])
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link {{ Nav::hasSegment('categories', 1, 'active') }}">
                        <i class="nav-icon fas fa-cube"></i>
                        <p>{{ trans_choice('modules.category', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @canany(['locale.create', 'locale.read', 'locale.update', 'locale.delete'])
                <li class="nav-item {{ Nav::hasSegment('locale', 1, 'menu-open') }}">
                    <a href="#" class="nav-link {{ Nav::hasSegment('locale', 1, 'active') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            {{ trans_choice('modules.locale', 2) }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('locale.country-states.index') }}" class="nav-link {{ Nav::hasSegment('country-states', 2, 'active') }}">
                                <i class="nav-icon"></i>
                                <p>{{ trans_choice('modules.country_state', 2) }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('locale.languages.index') }}" class="nav-link {{ Nav::hasSegment('languages', 2, 'active') }}">
                                <i class="nav-icon"></i>
                                <p>{{ trans_choice('modules.language', 2) }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany

                @canany(['activity_log.create', 'activity_log.read', 'activity_log.update', 'activity_log.delete'])
                <li class="nav-item">
                    <a href="{{ route('activity-logs.index') }}" class="nav-link {{ Nav::hasSegment('activity-logs', 1, 'active') }}">
                        <i class="nav-icon fas fa-stream"></i>
                        <p>{{ __('modules.activity_logs') }}</p>
                    </a>
                </li>
                @endcanany

                @endcanany

                {{-- END SETTINGS --}}

            </ul>
        </nav>
    </div>
</aside>