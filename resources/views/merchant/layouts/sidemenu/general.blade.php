<li class="nav-header">{{ __('modules.general') }}</li>

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

@mainBranch
<li class="nav-item">
    <a href="{{ route('merchant.branches.index') }}" class="nav-link {{ Nav::hasSegment('branches', 1, 'active') }}">
        <i class="nav-icon fas fa-store"></i>
        <p>{{ trans_choice('modules.branch', 2) }}</p>
    </a>
</li>
@endmainBranch