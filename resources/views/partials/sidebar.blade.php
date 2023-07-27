<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard.index') }}">
        <div class="sidebar-brand-text mx-3">{{ __('Homepage') }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('admin/dashboard') || request()->is('admin/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('Dashboard') }}</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseUser" aria-expanded="true"
            aria-controls="collapseUser">
            <span>{{ __('User Management') }}</span>
        </a>
        <div id="collapseUser" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}"
                    href="{{ route('admin.permissions.index') }}"> <i class="fa fa-briefcase mr-2"></i>
                    {{ __('Permissions') }}</a>
                <a class="collapse-item {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}"
                    href="{{ route('admin.roles.index') }}"><i class="fa fa-briefcase mr-2"></i>
                    {{ __('Roles') }}</a>
                <a class="collapse-item {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}"
                    href="{{ route('admin.users.index') }}"> <i class="fa fa-user mr-2"></i> {{ __('Users') }}</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseBook" aria-expanded="true"
            aria-controls="collapseBook">
            <span>{{ __('Booking Management') }}</span>
        </a>
        <div id="collapseBook" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('admin/studios') || request()->is('admin/studios/*') ? 'active' : '' }}"
                    href="{{ route('admin.studios.index') }}"> <i class="fa fa-briefcase mr-2"></i>
                    {{ __('Studio') }}</a>
                <a class="collapse-item {{ request()->is('admin/bookings') || request()->is('admin/bookings/*') ? 'active' : '' }}"
                    href="{{ route('admin.bookings.index') }}"> <i class="fa fa-briefcase mr-2"></i>
                    {{ __('Booking Studio') }}</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePaket" aria-expanded="true"
            aria-controls="collapsePaket">
            <span>{{ __('Service') }}</span>
        </a>
        <div id="collapsePaket" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('admin/services') || request()->is('admin/services/*') ? 'active' : '' }}"
                    href="{{ route('admin.services.index') }}"> <i class="fa fa-briefcase mr-2"></i>
                    {{ __('Paket') }}</a>
                <a class="collapse-item {{ request()->is('admin/bookings') || request()->is('admin/bookings/*') ? 'active' : '' }}"
                    href="{{ route('admin.bookingpaket.index') }}"> <i class="fa fa-briefcase mr-2"></i>
                    {{ __('Booking Paket') }}</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseLaporan" aria-expanded="true"
            aria-controls="collapseLaporan">
            <span>{{ __('Laporan Booking Paket') }}</span>
        </a>
        <div id="collapseLaporan" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('admin/laporan') || request()->is('admin/laporan/*') ? 'active' : '' }}"
                    href="{{ route('admin.laporan.index') }}"> <i class="fa fa-briefcase mr-2"></i>
                    {{ __('Laporan Paket') }}</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseLaporanboking" aria-expanded="true"
            aria-controls="collapseLaporanboking">
            <span>{{ __('Laporan Booking ') }}</span>
        </a>
        <div id="collapseLaporanboking" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('admin/laporan_boking') || request()->is('admin/laporan_boking/*') ? 'active' : '' }}"
                    href="{{ route('admin.laporan.booking') }}"> <i class="fa fa-briefcase mr-2"></i>
                    {{ __('Laporan Boking') }}</a>
            </div>
        </div>
    </li>


</ul>
