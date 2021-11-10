<nav class="main-header navbar navbar-expand navbar-white navbar-light border-0">

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a data-toggle="dropdown" class="nav-link" href="#">
                <img src="https://ui-avatars.com/api/?background=8D60D8&color=ffffff&size=35&rounded=true&name={{ urlencode(Auth::user()->name) }}" class="img-circle elevation-2" alt="user">
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="#" class="dropdown-item" onclick="event.preventDefault(); logoutAlert('{{ __('messages.confirm_question') }}');">
                    <i class="fas fa-sign-out-alt mr-2 text-danger"></i>
                    <span>{{ __('labels.logout') }}</span>
                </a>
                <form id="logout-form" action="{{ route('merchant.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>

</nav>