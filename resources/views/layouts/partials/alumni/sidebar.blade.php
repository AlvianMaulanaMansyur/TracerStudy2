<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('admin/dashboard') }}">
        <div class="sidebar-brand-icon me-2">
            <img src="{{ asset('../images/PNB.png') }}" alt="PNB logo" class="w-14">
        </div>
        <div class="sidebar-brand-text m">SITIKA</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
<div class="ms-2">
    <li class="nav-item {{ request()->routeIs('alumni.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
</div>  
    <!-- Divider -->
    <hr class="sidebar-divider">
</ul>
