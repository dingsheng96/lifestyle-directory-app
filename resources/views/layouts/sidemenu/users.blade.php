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
        <i class="nav-icon fas fa-user-cog"></i>
        <p>{{ trans_choice('modules.admin', 2) }}</p>
    </a>
</li>
@endcanany

@endcanany