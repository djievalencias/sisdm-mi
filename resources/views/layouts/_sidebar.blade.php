<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="https://api.dicebear.com/9.x/pixel-art/svg"
                class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block">{{ Auth::user()->nama }}</a>
        </div>
    </div>

     <!-- Sidebar Menu -->
     <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Dashboard menu item, visible to all users -->
            <li class="nav-item">
                <a href="{{ url('/home') }}" class="nav-link active">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <!-- Attendance and Users menu, only visible to admin -->
            @if (auth()->check() && auth()->user()->is_admin)
                <li class="nav-item">
                    <a href="{{ url('/attendance') }}" class="nav-link">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Attendance</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/user') }}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/shift') }}" class="nav-link">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>Shifts</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/pengumuman') }}" class="nav-link">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>Pengumuman</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/cuti-perizinan') }}" class="nav-link">
                        <i class="nav-icon fas fa-calendar-minus"></i>
                        <p>Cuti/Perizinan</p>
                    </a>
                </li>        
            @endif

            <li class="nav-header">LABELS</li>
            <li class="nav-item">
                <a href="{{ route('logout') }}" class="nav-link" 
                   onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                    <i class="nav-icon far fa-circle text-danger"></i>
                    <p class="text">Logout</p>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
