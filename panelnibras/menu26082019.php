<div class="collapse navbar-collapse" id="menuku">
    <!-- menu samping -->

    <ul class="nav navbar-nav menu-samping in" id="menu-samping">
        <li id="menu-dashboard"><a href="<?php echo URL_PROGRAM_ADMIN ?>home"><span class="glyphicon glyphicon-home"></span> Home</a></li>
        <li class="dropdown"><a href='javascript:void(0)' id="mnmaster" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> Master Data <b class="caret"></b></a>
            <ul class="dropdown-menu" id="dropmnmaster">
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "negara/" ?>"><span class="glyphicon glyphicon-record"></span> Negara</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "propinsi/" ?>"><span class="glyphicon glyphicon-record"></span> Propinsi</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "kabupaten/" ?>"><span class="glyphicon glyphicon-record"></span> Kotamadya/Kabupaten</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "kecamatan/" ?>"><span class="glyphicon glyphicon-record"></span> Kecamatan</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "status-order/" ?>"><span class="glyphicon glyphicon-record"></span> Status Order</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "bank/" ?>"><span class="glyphicon glyphicon-record"></span> Bank</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "rekening-bank/" ?>"><span class="glyphicon glyphicon-record"></span> Rekening Bank</a></li>
            </ul>
        </li>
        <li class="dropdown"><a href='javascript:void(0)' id="mnshipping" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-compressed"></span> Shipping <b class="caret"></b></a>
            <ul class="dropdown-menu" id="dropmnshipping">
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "shipping/" ?>"><span class="glyphicon glyphicon-record"></span> Kurir</a></li>
            </ul>
        </li>
        <li class="dropdown"><a href='javascript:void(0)' id="mnpelanggan" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-tower"></span> Pelanggan <b class="caret"></b></a>
            <ul class="dropdown-menu" id="dropmnpelanggan">
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "customer-grup/" ?>"><span class="glyphicon glyphicon-record"></span> Grup Pelanggan</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "customer/" ?>"><span class="glyphicon glyphicon-record"></span> Pelanggan</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "customer-poin/" ?>"><span class="glyphicon glyphicon-record"></span> Poin Pelanggan</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "customer-saldo/" ?>"><span class="glyphicon glyphicon-record"></span> Saldo Pelanggan</a></li>
            </ul>
        </li>
        <li class="dropdown"><a href='javascript:void(0)' id="mnkatalog" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-credit-card"></span> Katalog <b class="caret"></b></a>
            <ul class="dropdown-menu" id="dropmnkatalog">
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "warna/" ?>"><span class="glyphicon glyphicon-record"></span> Warna</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "ukuran/" ?>"><span class="glyphicon glyphicon-record"></span> Ukuran</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "kategori/" ?>"><span class="glyphicon glyphicon-record"></span> Kategori</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "produk-head/" ?>"><span class="glyphicon glyphicon-record"></span> Head Produk</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "produk/" ?>"><span class="glyphicon glyphicon-record"></span> Produk</a></li>
            </ul>
        </li>
        <li><a href='<?php echo URL_PROGRAM_ADMIN . "order/" ?>' id="mnorder"><span class="glyphicon glyphicon-shopping-cart"></span> Order </a></li>
        <li class="dropdown"><a href='javascript:void(0)' id="mnorder" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-tasks"></span> Laporan <b class="caret"></b></a>
            <ul class="dropdown-menu" id="dropmnorder">
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "lap-order" ?>"><span class="glyphicon glyphicon-record"></span> Order</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "lap-order?op=view_daily" ?>"><span class="glyphicon glyphicon-record"></span> Order Daily</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "lap-customer" ?>"><span class="glyphicon glyphicon-record"></span> Pelanggan</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "lap-customer?op=view_daily" ?>"><span class="glyphicon glyphicon-record"></span> Pelanggan Daily</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "lap-produk" ?>"><span class="glyphicon glyphicon-record"></span> Produk</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "lap-produk?op=bykategori" ?>"><span class="glyphicon glyphicon-record"></span> Produk per Kategori</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "lap-produklaris" ?>"><span class="glyphicon glyphicon-record"></span> 10 Produk Terlaris</a></li>
            </ul>
        </li>
        <li class="dropdown"><a href='javascript:void(0)' id="mnuser" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> User Administrator <b class="caret"></b></a>
            <ul class="dropdown-menu" id="dropmnorder">
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "grup-user/" ?>"><span class="glyphicon glyphicon-record"></span> Grup User</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "user/" ?>"><span class="glyphicon glyphicon-record"></span> User</a></li>
            </ul>
        </li>

        <li class="dropdown"><a href='javascript:void(0)' id="mnpengaturan" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> Pengaturan <b class="caret"></b></a>
            <ul class="dropdown-menu" id="dropmnpengaturan">
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "setting-toko/" ?>"><span class="glyphicon glyphicon-record"></span> Setting Toko</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "slideshow/" ?>"><span class="glyphicon glyphicon-record"></span> Slideshow</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "customer-support/" ?>"><span class="glyphicon glyphicon-record"></span> Customer Support</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "informasi/" ?>"><span class="glyphicon glyphicon-record"></span> Informasi</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "testimonial/" ?>"><span class="glyphicon glyphicon-record"></span> Testimonial</a></li>
            </ul>
        </li>
    </ul>

    <!-- /menu samping -->

    <ul class="nav menu-atas navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION["userlogin"] ?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="#">Profil</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . '?keluar' ?>">Keluar</a></li>
            </ul>
        </li>
        <li><a href="<?php echo URL_PROGRAM ?>" target="_blank"><span class="glyphicon glyphicon-eye-open"></span> Lihat Situs</a></li>
    </ul>

</div><!-- /.navbar-collapse -->
</nav>