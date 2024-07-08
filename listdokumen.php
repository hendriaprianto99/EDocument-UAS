<?php
session_start();

// Memeriksa apakah pengguna telah login, jika tidak, redirect ke halaman index.php
if (!isset($_SESSION["Email"])) {
    header("location: index.php");
    exit(); // Menghentikan eksekusi script setelah redirect
}

// Menginclude file-file yang diperlukan
include("db.php");
include("header.php");
include("menu.php");
?>

<div id="page-wrapper">
    <?php
    // Mengecek hak akses pengguna
    $q = "SELECT * FROM tw_hak_akses WHERE tabel='dokumen' AND user = '" . $_SESSION['Email'] . "' AND listData='1'";
    $r = mysqli_query($con, $q);

    // Jika pengguna memiliki akses
    if ($obj = mysqli_fetch_object($r)) {
        echo "<br><font face='Verdana' color='black' size='1'>dokumen</font><br><br>";
        echo "<a href='insertdokumen.php'><button type='button' class='btn btn-light'><font face='Verdana' color='black' size='1'><i class='fa fa-plus'></i>&nbsp;Insert</font></button></a>";
        echo "&nbsp;&nbsp;<a href='printdokumen.php' target='_blank'><button type='button' class='btn btn-light'><font face='Verdana' color='black' size='1'><i class='fa fa-print'></i>&nbsp;Print</font></button></a>";

        // Form pencarian
        echo "<br><br><form action='listdokumen.php' method='post'>";
        echo "<select class='form-control' name='select'>";
        $menu = mysqli_query($con, "SHOW COLUMNS FROM dokumen");
        while ($rowmenu = mysqli_fetch_array($menu)) {
            echo "<option value='" . $rowmenu['Field'] . "'>" . $rowmenu['Field'] . "</option>";
        }
        echo "</select>";
        echo "<input type='text' class='form-control' name='cari'>";
        echo "<button type='submit' class='btn btn-success'><font face='Verdana' size='1'><i class='fa fa-search-plus'></i>Search</font></button>";
        echo "</form><br>";

        // Jika ada data yang dicari
        if (isset($_POST["cari"]) && $_POST["cari"] != "") {
            $cari = mysqli_real_escape_string($con, $_POST["cari"]);
            $dd = "SELECT * FROM dokumen WHERE " . $_POST["select"] . " LIKE '%" . $cari . "%'";
            $resultcari = mysqli_query($con, $dd);

            // Jika hasil pencarian ditemukan
            if ($obj = mysqli_fetch_object($resultcari)) {
                // Tampilkan hasil pencarian
                echo "<font face='Verdana' color='black' size='1'>Hasil Pencarian</font>";
                echo "<div class='table-responsive'> ";
                echo "<table class='table table-striped'> 
                        <tr bgcolor='D3DCE3'> 
                            <th></th><th></th><th></th>
                            <th><font face='Verdana' color='black' size='1'>No_Dokumen</font></th>
                            <th><font face='Verdana' color='black' size='1'>Kode</font></th>
                            <th><font face='Verdana' color='black' size='1'>Judul</font></th>
                            <th><font face='Verdana' color='black' size='1'>Deskripsi</font></th>
                            <th><font face='Verdana' color='black' size='1'>Tanggal_Pembuatan</font></th>
                            <th><font face='Verdana' color='black' size='1'>Tanggal_Modifikasi</font></th>
                            <th><font face='Verdana' color='black' size='1'>Kode_Pengguna</font></th>
                        </tr>";

                $warna = 0;
                while ($row = mysqli_fetch_array($resultcari)) {
                    // Tampilkan baris hasil pencarian
                    // Perhatikan penggunaan htmlspecialchars() untuk menghindari XSS
                    echo "<tr bgcolor='" . ($warna == 0 ? 'E5E5E5' : 'D5D5D5') . "' onMouseOver=\"this.bgColor='#8888FF';\" onMouseOut=\"this.bgColor='" . ($warna == 0 ? 'E5E5E5' : 'D5D5D5') . "';\">";
                    // Kolom aksi (view, edit, delete)
                    echo "<td><a class='linklist' href='viewdokumen.php?No_Dokumen=" . $row['No_Dokumen'] . "'><button type='button' class='btn btn-warning'><font face='Verdana' size='1'><i class='fa fa-check'></i></font></button></a></td>";
                    echo "<td><a class='linklist' href='editdokumen.php?No_Dokumen=" . $row['No_Dokumen'] . "'><button type='button' class='btn btn-primary'><font face='Verdana' size='1'><i class='fa fa-edit'></i></font></button></a></td>";
                    echo "<td><a class='linklist' href='deletedokumen.php?No_Dokumen=" . $row['No_Dokumen'] . "' onclick=\"return confirm('Are you sure you want to delete this data?')\"><button type='button' class='btn btn-danger'><font face='Verdana' size='1'><i class='fa fa-trash'></i></font></button></a></td>";
                    // Data dari tabel dokumen
                    echo "<td><font face='Verdana' color='black' size='1'>" . htmlspecialchars($row['No_Dokumen']) . "</font></td>";
                    echo "<td><font face='Verdana' color='black' size='1'>" . htmlspecialchars($row['Kode']) . "<br>";
                    // Kolom Kategori (Ambil dari tabel kategori_dokumen)
                    $l = mysqli_query($con, "SELECT Kategori FROM kategori_dokumen WHERE Kode = '" . $row['Kode'] . "'");
                    while ($rl = mysqli_fetch_array($l)) {
                        echo htmlspecialchars($rl[0]);
                    }
                    echo "</font></td>";
                    echo "<td><font face='Verdana' color='black' size='1'>" . htmlspecialchars($row['Judul']) . "</font></td>";
                    echo "<td><font face='Verdana' color='black' size='1'>" . htmlspecialchars($row['Deskripsi']) . "</font></td>";
                    echo "<td><font face='Verdana' color='black' size='1'>" . htmlspecialchars($row['Tanggal_Pembuatan']) . "</font></td>";
                    echo "<td><font face='Verdana' color='black' size='1'>" . htmlspecialchars($row['Tanggal_Modifikasi']) . "</font></td>";
                    echo "<td><font face='Verdana' color='black' size='1'>" . htmlspecialchars($row['Kode_Pengguna']) . "<br>";
                    // Kolom Nama (Ambil dari tabel pengguna)
                    $l = mysqli_query($con, "SELECT Nama FROM pengguna WHERE Kode_Pengguna = '" . $row['Kode_Pengguna'] . "'");
                    while ($rl = mysqli_fetch_array($l)) {
                        echo htmlspecialchars($rl[0]);
                    }
                    echo "</font></td>";
                    echo "</tr>";
                    $warna = 1 - $warna;
                }
                echo "</table><br><br>";
                echo "</div>";
            } else {
                // Jika data tidak ditemukan
                echo "<font size='2' face='Verdana' color='#FF0000'>Data dokumen not found - try again!</font><br><br>";
            }
        } // Jika tidak ada pencarian
        else {
            // Langkah 1: Tentukan batas,cek halaman & posisi data
            $batas = 100;
            if (isset($_GET["halaman"])) {
                $halaman = $_GET['halaman'];
            }
            if (empty($halaman)) {
                $posisi = 0;
                $halaman = 1;
            } else {
                $posisi = ($halaman - 1) * $batas;
            }
            $result = mysqli_query($con, "SELECT * FROM dokumen LIMIT $posisi,$batas");

            // Tampilkan data dalam tabel
            echo "<div class='table-responsive'> ";
            echo "<table class='table table-striped'>"; 
            echo "<tr bgcolor='D3DCE3'> 
                    <th></th><th></th><th></th>
                    <th><font face='Verdana' color='black' size='1'>No_Dokumen</font></th>
                    <th><font face='Verdana' color='black' size='1'>Kode</font></th>
                    <th><font face='Verdana' color='black' size='1'>Judul</font></th>
                    <th><font face='Verdana' color='black' size='1'>Deskripsi</font></th>
                    <th><font face='Verdana' color='black' size='1'>Tanggal_Pembuatan</font></th>
                    <th><font face='Verdana' color='black' size='1'>Tanggal_Modifikasi</font></th>
                    <th><font face='Verdana' color='black' size='1'>Kode_Pengguna</font></th>
                </tr>";

            $firstColumn = 1;
            $warna = 0;
            while ($row = mysqli_fetch_array($result)) {
                // Tampilkan baris data
                // Perhatikan penggunaan htmlspecialchars() untuk menghindari XSS
                echo "<tr bgcolor='" . ($warna == 0 ? 'E5E5E5' : 'D5D5D5') . "' onMouseOver=\"this.bgColor='#8888FF';\" onMouseOut=\"this.bgColor='" . ($warna == 0 ? 'E5E5E5' : 'D5D5D5') . "';\">";
                echo "<td><a class='linklist' href='viewdokumen.php?No_Dokumen=" . $row['No_Dokumen'] . "'><button type='button' class='btn btn-warning'><font face='Verdana' size='1'><i class='fa fa-check'></i></font></button></a></td>";
                echo "<td><a class='linklist' href='editdokumen.php?No_Dokumen=" . $row['No_Dokumen'] . "'><button type='button' class='btn btn-primary'><font face='Verdana' size='1'><i class='fa fa-edit'></i></font></button></a></td>";
                echo "<td><a class='linklist' href='deletedokumen.php?No_Dokumen=" . $row['No_Dokumen'] . "' onclick=\"return confirm('Are you sure you want to delete this data?')\"><button type='button' class='btn btn-danger'><font face='Verdana' size='1'><i class='fa fa-trash'></i></font></button></a></td>";
                // Data dari tabel dokumen
                echo "<td><font face='Verdana' color='black' size='1'>" . htmlspecialchars($row['No_Dokumen']) . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . htmlspecialchars($row['Kode']) . "<br>";
                // Kolom Kategori (Ambil dari tabel kategori_dokumen)
                $l = mysqli_query($con, "SELECT Kategori FROM kategori_dokumen WHERE Kode = '" . $row['Kode'] . "'");
                while ($rl = mysqli_fetch_array($l)) {
                    echo htmlspecialchars($rl[0]);
                }
                echo "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . htmlspecialchars($row['Judul']) . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . htmlspecialchars($row['Deskripsi']) . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . htmlspecialchars($row['Tanggal_Pembuatan']) . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . htmlspecialchars($row['Tanggal_Modifikasi']) . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . htmlspecialchars($row['Kode_Pengguna']) . "<br>";
                // Kolom Nama (Ambil dari tabel pengguna)
                $l = mysqli_query($con, "SELECT Nama FROM pengguna WHERE Kode_Pengguna = '" . $row['Kode_Pengguna'] . "'");
                while ($rl = mysqli_fetch_array($l)) {
                    echo htmlspecialchars($rl[0]);
                }
                echo "</font></td>";
                echo "</tr>";
                $warna = 1 - $warna;
            }
            echo "</table><br>";
            echo "</div>";

            // Langkah 3: Hitung total data dan halaman
            $tampil2 = mysqli_query($con, "SELECT * FROM dokumen");
            $jmldata = mysqli_num_rows($tampil2);
            $jmlhal  = ceil($jmldata / $batas);
            echo "<div class='paging'>";
            // Link ke halaman sebelumnya (previous)
            if ($halaman > 1) {
                $prev = $halaman - 1;
                echo "<span class='prevnext'><a href='$_SERVER[PHP_SELF]?halaman=$prev'><font face='Verdana' color='black' size='1'>&lt;&lt; Prev</font></a></span> ";
            } else {
                echo "<span class='disabled'><font face='Verdana' color='black' size='1'>&lt;&lt; Prev</font></span> ";
            }
            // Tampilkan link halaman 1,2,3 ...
            for ($i = 1; $i <= $jmlhal; $i++) {
                if ($i != $halaman) {
                    echo " <a href='$_SERVER[PHP_SELF]?halaman=$i'><font face='Verdana' color='black' size='1'>$i</font></a> ";
                } else {
                    echo " <span class='current'><font face='Verdana' color='black' size='1'>$i</font></span> ";
                }
            }
            // Link kehalaman berikutnya (Next)
            if ($halaman < $jmlhal) {
                $next = $halaman + 1;
                echo "<span class='prevnext'><a href='$_SERVER[PHP_SELF]?halaman=$next'><font face='Verdana' color='black' size='1'>Next &gt;&gt;</font></a></span>";
            } else {
                echo "<span class='disabled'><font face='Verdana' color='black' size='1'>Next &gt;&gt;</font></span>";
            }
            echo "</div>";
            echo "<p align='center'><font face='Verdana' color='black' size='1'><b>$jmldata</b> data</font></p>";
            echo "</td></tr>";
        }
    } else {
        // Jika pengguna tidak memiliki akses, tambahkan pesan atau redirect ke halaman lain
        echo "Anda tidak memiliki izin untuk mengakses halaman ini.";
    }
    ?>
</div>

<?php
include("footer.php");
?>
