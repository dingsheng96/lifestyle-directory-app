<li class="nav-header">{{ __('modules.settings') }}</li>

<li class="nav-item">
    <a href="{{ route('merchant.media.index') }}" class="nav-link {{ Nav::hasSegment('media', 1, 'active') }}">
        <i class="nav-icon fas fa-images"></i>
        <p>{{ __('modules.media') }}</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('merchant.operations.index') }}" class="nav-link {{ Nav::hasSegment('operations', 1, 'active') }}">
        <i class="nav-icon far fa-clock"></i>
        <p>{{ trans_choice('modules.operation', 2) }}</p>
    </a>
</li>