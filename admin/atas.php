<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <span class="d-none d-lg-block" style="font-size: 18px;"><i class="bi bi-bank"></i>&nbsp; MA AL-MARDLIYYAH PAMEKASAN</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <!-- <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li>End Search Icon -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <?php
            require "../db.php"; 
            $nisn = $_SESSION["admin"];
            $query = "SELECT nama_petugas FROM petugas WHERE id_petugas = ?";
            $stmt = mysqli_prepare($kon, $query);
            mysqli_stmt_bind_param($stmt, "s", $nisn);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);

            if ($row) {
                $nama_petugas = $row['nama_petugas'];
            } else {
                $nama_petugas = 'Petugas Tidak Ditemukan';
            }
            mysqli_stmt_close($stmt);
            ?>            <span class="d-none d-md-block dropdown-toggle ps-2"><i style="font-size: 20px" class="bi bi-person"></i>&nbsp; Halo, <?= htmlspecialchars($nama_petugas) ?>!</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
            <h6><?= htmlspecialchars($nama_petugas) ?></h6>
              <span>Anda sebagai Admin</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="profil.php">
                <i class="bi bi-person"></i>
                <span>Profile</span>
              </a>
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Log-Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header>