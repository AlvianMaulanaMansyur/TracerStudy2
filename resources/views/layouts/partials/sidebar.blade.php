<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('admin/dashboard') }}">
        <div class="sidebar-brand-icon me-2">
            <img src="{{ asset('../images/PNB.png') }}" alt="PNB logo" class="w-14">
        </div>
        <div class="sidebar-brand-text m">SITIKA <span class="text-yellow-500 block">(Admin)</span></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
<div class="ms-2">

    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Nav Item - Kuesioner -->
    <li class="ms-1 nav-item {{ request()->routeIs('kuesioner.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kuesioner.index') }}">
         <i class="fa-solid fa-clipboard-question"></i>
            <span>Kuesioner</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('rekap.index') ? 'active' : '' }} ">
        <a class="nav-link" href="{{ route('rekap.index') }}">
            <i class="fa-solid fa-users"></i>
            <span>Data responden</span>
        </a>
    </li>
</div>
    <!-- Divider -->
    <hr class="sidebar-divider">
</ul>
