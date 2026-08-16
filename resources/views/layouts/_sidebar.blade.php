@php
    $user = Auth::user();
    $initials = collect(explode(' ', $user->nama))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->join('');
    $isAdmin = $user->hasRole('admin');
    $isSupervisor = $user->bawahan()->exists();
@endphp

<div class="sidebar d-flex flex-column">

    <!-- Brand -->
    <div class="si-brand">
        <img src="{{ asset('assets/img/logo-mark.png') }}" alt="Mebel International" class="si-brand-logo">
        <div>
            <div class="si-brand-name">{{ __('HRIS') }}</div>
            <div class="si-brand-sub">Mebel International</div>
        </div>
    </div>

    <!-- Signed-in user -->
    <div class="si-user">
        <div class="si-avatar">{{ strtoupper($initials) }}</div>
        <div>
            <div class="si-user-name">{{ $user->nama }}</div>
            <div class="si-user-role">{{ $isAdmin ? __('Administrator') : ($isSupervisor ? __('Supervisor') : __('Employee')) }}</div>
        </div>
    </div>

    <!-- Menu -->
    <nav class="mt-1 flex-grow-1" style="overflow-y: auto;">
        <ul class="nav nav-sidebar flex-column" role="menu">
            <li class="nav-item">
                <a href="{{ url('/home') }}" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-th-large"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>

            @if ($isAdmin || $isSupervisor)
                <li class="nav-header">{{ __('People') }}</li>
                @if ($isAdmin)
                    <li class="nav-item">
                        <a href="{{ url('/user') }}" class="nav-link {{ request()->is('user*') || request()->is('kantor*') || request()->is('departemen*') || request()->is('grup*') || request()->is('jabatan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ __('Employees') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/attendance') }}" class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-fingerprint"></i>
                            <p>{{ __('Attendance') }}</p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ url('/cuti-perizinan') }}" class="nav-link {{ request()->is('cuti-perizinan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-umbrella-beach"></i>
                        <p>{{ __('Leave Requests') }}</p>
                    </a>
                </li>
            @endif

            @if ($isAdmin)
                <li class="nav-header">{{ __('Work') }}</li>
                <li class="nav-item">
                    <a href="{{ url('/shift') }}" class="nav-link {{ request()->is('shift*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>{{ __('Shifts') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/kalender') }}" class="nav-link {{ request()->is('kalender*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>{{ __('Work Calendar') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/payroll') }}" class="nav-link {{ request()->is('payroll*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-check-alt"></i>
                        <p>{{ __('Payroll') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ __('Company') }}</li>
                <li class="nav-item">
                    <a href="{{ url('/pengumuman') }}" class="nav-link {{ request()->is('pengumuman*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>{{ __('Announcements') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/activity-log') }}" class="nav-link {{ request()->is('activity-log*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>{{ __('Activity Log') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/failed-jobs') }}" class="nav-link {{ request()->is('failed-jobs*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-exclamation-triangle"></i>
                        <p>{{ __('Failed Jobs') }}</p>
                    </a>
                </li>
            @endif
        </ul>
    </nav>

    <!-- Logout pinned to the bottom -->
    <ul class="nav nav-sidebar flex-column si-logout">
        <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>{{ __('Sign out') }}</p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>
