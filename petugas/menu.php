<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

    <h5 class="text-center">MAIN MENU</h5>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "dashboard"){ echo "collapsed"; } ?>" href="index.php">
          <i class="bi bi-grid"></i>
          <span>DASHBOARD</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "transaksi"){ echo "collapsed"; } ?>" href="transaksi.php">
          <i class="bi bi-receipt-cutoff"></i>
          <span>DATA TRANSAKSI</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "tunggakan"){ echo "collapsed"; } ?>" href="tunggakan.php">
        <i class="bi bi-list-check"></i>
          <span>DATA TUNGGAKAN</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "angsuran"){ echo "collapsed"; } ?>" href="data_angsuran.php">
        <i class="bi bi-card-list"></i>
          <span>DATA ANGSURAN</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "keringanan"){ echo "collapsed"; } ?>" href="pengajuan_keringanan.php">
        <i class="bi bi-chat-square-dots"></i>
          <span>PENGAJUAN KERINGANAN</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "beasiswa"){ echo "collapsed"; } ?>" href="beasiswa.php">
          <i class="bi bi-journal"></i>
          <span>BEASISWA</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "riwayatpembayaran"){ echo "collapsed"; } ?>" href="history.php">
          <i class="bi bi-clock-history"></i>
          <span>RIWAYAT PEMBAYARAN</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "total_masuk"){ echo "collapsed"; } ?>" href="total_pemasukan.php">
          <i class="bi bi-bar-chart"></i>
          <span>TOTAL PEMASUKAN</span>
        </a>
      </li>

    </ul>

</aside>