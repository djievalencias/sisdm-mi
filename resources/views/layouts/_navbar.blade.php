@php
    $navUser = Auth::user();
    $navInitials = collect(explode(' ', $navUser->nama))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->join('');
@endphp

<nav class="main-header navbar navbar-expand">
    <!-- Left: sidebar toggle -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-flex">
            <span class="si-topdate">{{ now()->translatedFormat('l, d F Y') }}</span>
        </li>
    </ul>

    <!-- Right: user chip with dropdown -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link si-userchip" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <span class="si-avatar">{{ strtoupper($navInitials) }}</span>
                <span class="si-userchip-name d-none d-md-inline">{{ $navUser->nama }}</span>
                <i class="fas fa-chevron-down" style="font-size: 10px;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <span class="dropdown-item-text">
                    <strong>{{ $navUser->nama }}</strong><br>
                    <small class="text-muted">{{ $navUser->email }}</small>
                </span>
                <div class="dropdown-divider"></div>
                <h6 class="dropdown-header">{{ __('Language') }}</h6>
                <a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">
                    <i class="fas fa-check mr-2 {{ app()->getLocale() === 'en' ? '' : 'invisible' }}"></i> English
                </a>
                <a class="dropdown-item" href="{{ route('lang.switch', 'id') }}">
                    <i class="fas fa-check mr-2 {{ app()->getLocale() === 'id' ? '' : 'invisible' }}"></i> Bahasa Indonesia
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
                    <i class="fas fa-sign-out-alt mr-2 text-danger"></i> {{ __('Sign out') }}
                </a>
                <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
