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

@canany(['locale.create', 'locale.read', 'locale.update', 'locale.delete'])
<li class="nav-item {{ Nav::hasSegment('locale', 1, 'menu-open') }}">
    <a href="#" class="nav-link {{ Nav::hasSegment('locale', 1, 'active') }}">
        <i class="nav-icon fas fa-globe-asia"></i>
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