<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container mb-3 mt-3">
        <a class="navbar-brand " href="{{ route('home') }}">
            <img src="{{ asset('assets/image/MicrosoftTeams-image.png') }}" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto nav-text">
                <li class="nav-item ml-4">
                    <a class="nav-link" href="{{ route('home') }}#home">{{ trans('labels.menu.home') }}</a>
                </li>
                <li class="nav-item ml-4">
                    <a class="nav-link" href="{{ route('home') }}#about">{{ trans('labels.menu.about_us') }}</a>
                </li>
                <li class="nav-item ml-4">
                    <a class="nav-link" href="{{ route('home') }}#feature">{{ trans('labels.menu.feature') }}</a>
                </li>
                @if (isset($categories) && $categories->count() > 0)
                <li class="nav-item ml-4">
                    <a class="nav-link" href="{{ route('home') }}#industry">{{ trans('labels.menu.industry') }}</a>
                </li>
                @endif
                <li class="nav-item ml-4">
                    <a class="nav-link" href="{{ route('home') }}#contact">{{ trans('labels.menu.contact_us') }}</a>
                </li>
                <li class="nav-item ml-4">
                    <button class="btn btn-outline-light collapse-reg btn-style" type="button" onclick="location.href='{{ route('merchant.register') }}'">{{ trans('labels.menu.join_as_merchant') }}</button>
                </li>
            </ul>
        </div>
    </div>
</nav>