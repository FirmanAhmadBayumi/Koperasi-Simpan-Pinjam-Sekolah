<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark" style="display: flex; justify-content: center; align-items: center; height: 70px;">
            <a href="/adminDashboard" class="logo">
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
                <li class="{{request()->is('adminDashboard') ? 'nav-item active' : 'nav-item'}}">
                    <a href="/adminDashboard">
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
                <li class="{{request()->is('dataAnggota', 'register') ? 'nav-item active' : 'nav-item'}}">
                    <a href="/dataAnggota">
                        <i class="fas fa-address-book"></i>
                        <p>Data Anggota</p>
                    </a>
                </li>
                <li class="{{request()->is('dataSimpananPokok') ? 'nav-item active' : 'nav-item'}}">
                    <a href="/dataSimpananPokok">
                        <i class="fas fa-money-check-alt"></i>
                        <p>Data Simpanan Pokok</p>
                    </a>
                </li>
                <li class="{{request()->is('dataPinjaman') ? 'nav-item active' : 'nav-item'}}">
                    <a href="/dataPinjaman">
                        <i class="fas fa-money-bill-alt"></i>
                        <p>Data Pinjaman</p>
                    </a>
                </li>
                <li class="{{request()->is('dataTanggungan') ? 'nav-item active' : 'nav-item'}}">
                    <a href="/dataTanggungan">
                        <i class="fas fa-hand-holding-usd"></i>
                        <p>Data Tanggungan</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('transaksiSimpanan', 'transaksiPinjaman') ? 'active' : '' }}">
                    <a href="#" data-bs-toggle="collapse" data-bs-target="#submenuTransaksi" aria-expanded="{{ request()->is('transaksiSimpanan', 'transaksiPinjaman') ? 'true' : 'false' }}" aria-controls="submenuTransaksi">
                        <i class="fas fa-history"></i>
                        <p>Data Transaksi</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('transaksiSimpanan', 'transaksiPinjaman') ? 'show' : '' }}" id="submenuTransaksi">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('transaksiSimpanan') ? 'active' : '' }}">
                                <a href="/transaksiSimpanan">
                                    <span class="sub-item">Transaksi Simpanan</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('transaksiPinjaman') ? 'active' : '' }}">
                                <a href="/transaksiPinjaman">
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