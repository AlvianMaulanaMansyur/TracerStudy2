<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('admin/dashboard') }}">
        <div class="sidebar-brand-icon me-2">
            <img src="{{ asset('../images/PNB.png') }}" alt="PNB logo" class="w-14">
        </div>
        <div class="sidebar-brand-text m">
            <h1>SITIKA X <span>PNB</span></h1>

        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
<div class="ms-2">
    <li class="nav-item {{ request()->routeIs('alumni.profil') ? 'active' : '' }}">
        <a class="nav-link" href=" {{ route('alumni.profil') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('kuesioner.alumni.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kuesioner.alumni.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Kuesioner</span>
        </a>
    </li>
</div>
    <!-- Divider -->
    <hr class="sidebar-divider">
</ul>
