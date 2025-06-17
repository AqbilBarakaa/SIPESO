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
        <a class="nav-link <?php if($page == "siswa"){ echo "collapsed"; } ?>" href="siswa.php">
          <i class="bi bi-person"></i>
          <span>DATA SISWA</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "petugas"){ echo "collapsed"; } ?>" href="petugas.php">
          <i class="bi bi-person-badge"></i>
          <span>DATA PETUGAS</span>
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link <?php if($page == "kelas"){ echo "collapsed"; } ?>" href="kelas.php">
          <i class="bi bi-pass"></i>
          <span>DATA KELAS</span>
        </a>
      </li>

      <li class="nav-item"> 
        <a class="nav-link <?php if($page == "spp"){ echo "collapsed"; } ?>" href="spp.php">
          <i class="bi bi-wallet2"></i>
          <span>DATA SPP</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "tanyajawab"){ echo "collapsed"; } ?>" href="tanyajawab.php">
          <i class="bi bi-question-circle"></i>
          <span>TANYA JAWAB</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "beasiswa"){ echo "collapsed"; } ?>" href="beasiswa.php">
          <i class="bi bi-journal"></i>
          <span>BEASISWA</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "riwayatpembayaran"){ echo "collapsed"; } ?>" href="riwayatpembayaran.php">
          <i class="bi bi-clock-history"></i>
          <span>RIWAYAT PEMBAYARAN</span>
        </a>
      </li>

    </ul>

</aside>