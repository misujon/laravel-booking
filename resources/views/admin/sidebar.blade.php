<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">M.i.sujon</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <li class="nav-item active">
        <a class="nav-link" href="{{ route('computer.index') }}">
            <i class="fas fa-fw fa-laptop"></i>
            <span>Computer Services</span></a>
    </li>

    <li class="nav-item active">
        <a class="nav-link" href="{{ route('booking.index') }}">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Services Bookings</span></a>
    </li>
    @if(Auth::guard('admin')->user()->role == 1)

    <li class="nav-item active">
        <a class="nav-link" href="{{ route('staff.index') }}">
            <i class="fas fa-fw fa-user-secret"></i>
            <span>Staffs</span></a>
    </li>

    <li class="nav-item active">
        <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Users</span></a>
    </li>

    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
