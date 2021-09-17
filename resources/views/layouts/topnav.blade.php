<nav class="main-header navbar navbar-expand navbar-white navbar-light border-0">

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">

        <li class="nav-item">
            <a href="#" class="nav-link btn" onclick="event.preventDefault(); logoutAlert('{{ __('messages.confirm_question') }}');">
                <i class="fas fa-sign-out-alt text-danger"></i>
            </a>
            <form id="logout-form" action="{{ route('merchant.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>

</nav>