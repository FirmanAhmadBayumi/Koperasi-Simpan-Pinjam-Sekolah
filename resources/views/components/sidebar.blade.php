<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark" style="display: flex; justify-content: center; align-items: center; height: 70px;">
            <a href="/dashboard" class="logo">
                <img src="assets/img/kaiadmin/sideBarLogo.png" alt="navbar brand" class="navbar-brand" height="160" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="{{request()->is('dashboard', 'profile') ? 'nav-item active' : 'nav-item'}}">
                    <a href="/dashboard">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Menu</h4>
                </li>
                <li class="{{request()->is('pengajuan') ? 'nav-item active' : 'nav-item'}}">
                    <a href="/pengajuan">
                        <i class="fas fa-plus-circle"></i>
                        <p>Pengajuan Pinjaman</p>
                    </a>
                </li>
                <li class="{{request()->is('tanggungan') ? 'nav-item active' : 'nav-item'}}">
                    <a href="/tanggungan">
                        <i class="fas fa-donate"></i>
                        <p>Tanggungan Anggota</p>
                    </a>
                </li>
                <li class="{{request()->is('history') ? 'nav-item active' : 'nav-item'}}">
                    <a href="/history">
                        <i class="fas fa-hand-holding-usd"></i>
                        <p>Riwayat Pinjaman</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('transaksiSimpananUser', 'transaksiPinjamanUser') ? 'active' : '' }}">
                    <a href="#" data-bs-toggle="collapse" data-bs-target="#submenuTransaksi" aria-expanded="{{ request()->is('transaksiSimpanan', 'transaksiPinjaman') ? 'true' : 'false' }}" aria-controls="submenuTransaksi">
                        <i class="fas fa-history"></i>
                        <p>Data Transaksi</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('transaksiSimpananUser', 'transaksiPinjamanUser') ? 'show' : '' }}" id="submenuTransaksi">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('transaksiSimpananUser') ? 'active' : '' }}">
                                <a href="/transaksiSimpananUser">
                                    <span class="sub-item">Transaksi Simpanan</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('transaksiPinjamanUser') ? 'active' : '' }}">
                                <a href="/transaksiPinjamanUser">
                                    <span class="sub-item">Transaksi Pinjaman</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="{{request()->is('logout') ? 'nav-item active' : 'nav-item'}}">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="logout" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <p>Keluar</p>
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>