<li class="nav-header">{{ __('modules.settings') }}</li>

<li class="nav-item">
    <a href="{{ route('merchant.media.index') }}" class="nav-link {{ Nav::hasSegment('media', 1, 'active') }}">
        <i class="nav-icon fas fa-images"></i>
        <p>{{ __('modules.media') }}</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('merchant.profile.index') }}" class="nav-link {{ Nav::hasSegment('profile', 1, 'active') }}">
        <i class="nav-icon fas fa-user"></i>
        <p>{{ __('labels.my_profile') }}</p>
    </a>
</li>