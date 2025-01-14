<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('admin/dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('../images/PNB.png') }}" alt="PNB logo" class="w-14">
        </div>
        <div class="sidebar-brand-text m">SITIKUS <span class="text-yellow-500">(Admin)</span> </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Nav Item - Kuesioner -->
    <li class="nav-item {{ request()->routeIs('kuesioner.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kuesioner.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Kuesioner</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('rekap.index') ? 'active' : '' }} ">
        <a class="nav-link" href="{{ route('rekap.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Data responden</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
</ul>
