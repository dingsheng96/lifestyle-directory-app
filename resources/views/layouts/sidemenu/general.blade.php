<li class="nav-header">{{ __('modules.general') }}</li>

<li class="nav-item">
    <a href="{{ route('dashboard') }}" class="nav-link {{ Nav::hasSegment('dashboard', 1, 'active') }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>{{ __('modules.dashboard') }}</p>
    </a>
</li>

@canany(['category.create', 'category.read', 'category.update', 'category.delete'])
<li class="nav-item">
    <a href="{{ route('categories.index') }}" class="nav-link {{ Nav::hasSegment('categories', 1, 'active') }}">
        <i class="nav-icon fas fa-shapes"></i>
        <p>{{ trans_choice('modules.category', 2) }}</p>
    </a>
</li>
@endcanany