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
        <a class="nav-link <?php if($page == "tagihan"){ echo "collapsed"; } ?>" href="tagihan.php">
          <i class="bi bi-clock-history"></i>
          <span>TAGIHAN SPP</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "history"){ echo "collapsed"; } ?>" href="history.php">
          <i class="bi bi-clock-history"></i>
          <span>RIWAYAT PEMBAYARAN</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "tanyajawab"){ echo "collapsed"; } ?>" href="tanyajawab.php">
          <i class="bi bi-question-circle"></i>
          <span>TANYA JAWAB</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "pengajuan"){ echo "collapsed"; } ?>" href="form_pengajuan.php">
          <i class="bi bi-chat-square-dots"></i>
          <span>PENGAJUAN KERINGANAN</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "about"){ echo "collapsed"; } ?>" href="about.php">
          <i class="bi bi-info"></i>
          <span>ABOUT</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page == "media_sosial"){ echo "collapsed"; } ?>" href="medsos.php">
          <i class="bi bi-instagram"></i>
          <span>MEDIA SOSIAL</span>
        </a>
      </li>

    </ul>

</aside>