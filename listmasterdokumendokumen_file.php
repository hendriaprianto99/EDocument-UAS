<?php
session_start();
if (!isset($_SESSION["Email"])) {
    header("location:index.php");
    exit();
}

include("db.php");
include("header.php");
include("menu.php");
?>

<div id="page-wrapper">
<?php
// Cek otoritas
$q = "SELECT * FROM tw_hak_akses WHERE tabel='dokumen/dokumen_file' AND user = '". $_SESSION['Email'] ."' AND listData='1'";
$r = mysqli_query($con, $q);
if ($obj = mysqli_fetch_object($r)) {
?>
    <br>
    <font face="Verdana" color="black" size="1">dokumen</font><br>
    <br>
    <a href="insertmasterdokumendokumen_file.php">
        <button type="button" class="btn btn-light">
            <font face="Verdana" color="black" size="1"><i class="fa fa-plus"></i>&nbsp;Insert</font>
        </button>
    </a><br>
    <br>
    <form action="listmasterdokumendokumen_file.php" method="post">
        <select class="form-control" name="select">
            <?php
            $menu = mysqli_query($con, "SHOW COLUMNS FROM dokumen");
            while ($rowmenu = mysqli_fetch_array($menu)) {
                echo "<option value='". $rowmenu['Field'] ."'>". $rowmenu['Field'] ."</option>";
            }
            ?>
        </select>
        <input type="text" class="form-control" name="cari">
        <button type="submit" class="btn btn-success">
            <font face="Verdana" size="1"><i class="fa fa-search-plus"></i>Search</font>
        </button>
    </form><br><br>
    <?php
    if (isset($_POST["cari"]) && ($_POST["cari"] != "")){
        // Hasil pencarian tabel
        $dd = "SELECT * FROM dokumen WHERE ". $_POST["select"] ." LIKE '%" . $_POST["cari"] . "%'";
        $resultcari = mysqli_query($con, $dd);
        if ($obj = mysqli_fetch_object($resultcari)) {
            echo "<font face='Verdana' color='black' size='1'>Hasil Pencarian</font>"; 
            echo "<div class='table-responsive'> "; 
            echo "<table class='table table-striped'> 
            <tr bgcolor='D3DCE3'> 
                <td></td><td></td><td></td><td></td>
                <td><font face='Verdana' color='black' size='1'><b>No_Dokumen</b></font></td>
                <td><font face='Verdana' color='black' size='1'><b>Kode</b></font></td>
                <td><font face='Verdana' color='black' size='1'><b>Judul</b></font></td>
                <td><font face='Verdana' color='black' size='1'><b>Deskripsi</b></font></td>
                <td><font face='Verdana' color='black' size='1'><b>Tanggal_Pembuatan</b></font></td>
                <td><font face='Verdana' color='black' size='1'><b>Tanggal_Modifikasi</b></font></td>
                <td><font face='Verdana' color='black' size='1'><b>Kode_Pengguna</b></font></td>
            </tr>";
            $warna = 0;
            while ($row = mysqli_fetch_array($resultcari)) {
                $warna = ($warna == 0) ? 1 : 0;
                $bg_color = ($warna == 1) ? 'E5E5E5' : 'D5D5D5';
                echo "<tr bgcolor='$bg_color' onMouseOver=\"this.bgColor='#8888FF';\" onMouseOut=\"this.bgColor='$bg_color';\">";
                echo "<td><a class='linklist' href='listmasterdokumendokumen_filedetail.php?No_Dokumen=".$row['No_Dokumen']."'><font face='Verdana' color='black' size='1'>Detail</font></a></td>";
                echo "<td><a class='linklist' href='viewmasterdokumendokumen_file.php?No_Dokumen=".$row['No_Dokumen']."'><button type='button' class='btn btn-warning'><font face='Verdana' size='1'><i class='fa fa-check'></i></font></button></a></td>";
                echo "<td><a class='linklist' href='editmasterdokumendokumen_file.php?No_Dokumen=".$row['No_Dokumen']."'><button type='button' class='btn btn-primary'><font face='Verdana' size='1'><i class='fa fa-edit'></i></font></button></a></td>";
                echo "<td><a class='linklist' href='deletemasterdokumendokumen_file.php?No_Dokumen=".$row['No_Dokumen']."' onclick=\"return confirm('Are you sure you want to delete this data?')\"><button type='button' class='btn btn-danger'><font face='Verdana' size='1'><i class='fa fa-trash'></i></font></button></a></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['No_Dokumen'] . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['Kode'] . "<br>";
                $l = mysqli_query($con, "SELECT Kategori FROM kategori_dokumen WHERE Kode = '". $row['Kode'] ."'"); 
                while ($rl = mysqli_fetch_array($l)) {  
                    echo $rl[0];    
                } 
                echo "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['Judul'] . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['Deskripsi'] . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['Tanggal_Pembuatan'] . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['Tanggal_Modifikasi'] . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['Kode_Pengguna'] . "<br>";
                $l = mysqli_query($con, "SELECT Nama FROM pengguna WHERE Kode_Pengguna = '". $row['Kode_Pengguna'] ."'"); 
                while ($rl = mysqli_fetch_array($l)) {  
                    echo $rl[0];    
                } 
                echo "</font></td>";
                echo "</tr>";
            }
            echo "</table><br><br>";
            echo "</div>";
        } else {
            echo "<font size='2' face='Verdana' color='#FF0000'>Data dokumen not found - try again!</font><br><br>";
        }
    }

    if ((!isset($_POST["cari"])) or ($_POST["cari"] == "")) {
        // Langkah 1: Tentukan batas, cek halaman & posisi data
        $batas = 100;
        $halaman = isset($_GET['halaman']) ? $_GET['halaman'] : 1;
        $posisi = ($halaman-1) * $batas;
        $result = mysqli_query($con, "SELECT * FROM dokumen LIMIT $posisi, $batas");

        echo "<div class='table-responsive'>"; 
        echo "<table class='table table-striped'>";
        echo "<tr bgcolor='D3DCE3'>
                <td></td><td></td><td></td><td></td>
                <td><font face='Verdana' color='black' size='1'><b>No_Dokumen</b></font></td>
                <td><font face='Verdana' color='black' size='1'><b>Kode</b></font></td>
                <td><font face='Verdana' color='black' size='1'><b>Judul</b></font></td>
                <td><font face='Verdana' color='black' size='1'><b>Deskripsi</b></font></td>
                <td><font face='Verdana' color='black' size='1'><b>Tanggal_Pembuatan</b></font></td>
                <td><font face='Verdana' color='black' size='1'><b>Tanggal_Modifikasi</b></font></td>
                <td><font face='Verdana' color='black' size='1'><b>Kode_Pengguna</b></font></td>
            </tr>";
        $warna = 0;
        while ($row = mysqli_fetch_array($result)) {
            $warna = ($warna == 0) ? 1 : 0;
            $bg_color = ($warna == 1) ? 'E5E5E5' : 'D5D5D5';
            echo "<tr bgcolor='$bg_color' onMouseOver=\"this.bgColor='#8888FF';\" onMouseOut=\"this.bgColor='$bg_color';\">";
            echo "<td><a class='linklist' href='listmasterdokumendokumen_filedetail.php?No_Dokumen=".$row['No_Dokumen']."'><font face='Verdana' color='black' size='1'>Detail</font></a></td>";
            echo "<td><a class='linklist' href='viewmasterdokumendokumen_file.php?No_Dokumen=".$row['No_Dokumen']."'><button type='button' class='btn btn-warning'><font face='Verdana' size='1'><i class='fa fa-check'></i></font></button></a></td>";
            echo "<td><a class='linklist' href='editmasterdokumendokumen_file.php?No_Dokumen=".$row['No_Dokumen']."'><button type='button' class='btn btn-primary'><font face='Verdana' size='1'><i class='fa fa-edit'></i></font></button></a></td>";
            echo "<td><a class='linklist' href='deletemasterdokumendokumen_file.php?No_Dokumen=".$row['No_Dokumen']."' onclick=\"return confirm('Are you sure you want to delete this data?')\"><button type='button' class='btn btn-danger'><font face='Verdana' size='1'><i class='fa fa-trash'></i></font></button></a></td>";
            echo "<td><font face='Verdana' color='black' size='1'>" . $row['No_Dokumen'] . "</font></td>";
            echo "<td><font face='Verdana' color='black' size='1'>" . $row['Kode'] . "<br>";
            $l = mysqli_query($con, "SELECT Kategori FROM kategori_dokumen WHERE Kode = '". $row['Kode'] ."'"); 
            while ($rl = mysqli_fetch_array($l)) {  
                echo $rl[0];    
            } 
            echo "</font></td>";
            echo "<td><font face='Verdana' color='black' size='1'>" . $row['Judul'] . "</font></td>";
            echo "<td><font face='Verdana' color='black' size='1'>" . $row['Deskripsi'] . "</font></td>";
            echo "<td><font face='Verdana' color='black' size='1'>" . $row['Tanggal_Pembuatan'] . "</font></td>";
            echo "<td><font face='Verdana' color='black' size='1'>" . $row['Tanggal_Modifikasi'] . "</font></td>";
            echo "<td><font face='Verdana' color='black' size='1'>" . $row['Kode_Pengguna'] . "<br>";
            $l = mysqli_query($con, "SELECT Nama FROM pengguna WHERE Kode_Pengguna = '". $row['Kode_Pengguna'] ."'"); 
            while ($rl = mysqli_fetch_array($l)) {  
                echo $rl[0];    
            } 
            echo "</font></td>";
            echo "</tr>";
        }
        echo "</table><br>";
        echo "</div>";
        
        // Langkah 3: Hitung total data dan halaman
        $tampil2 = mysqli_query($con, "SELECT * FROM dokumen");
        $jmldata = mysqli_num_rows($tampil2);
        $jmlhal  = ceil($jmldata/$batas);
        echo "<div class='paging'>";
        // Link ke halaman sebelumnya (previous)
        if ($halaman > 1) {
            $prev = $halaman - 1;
            echo "<span class='prevnext'><a href='$_SERVER[PHP_SELF]?halaman=$prev'>&lt;&lt; Prev</a></span> ";
        } else {
            echo "<span class='disabled'>&lt;&lt; Prev</span> ";
        }
        // Tampilkan link halaman 1,2,3 ...
        for ($i = 1; $i <= $jmlhal; $i++) {
            if ($i != $halaman) {
                echo "<a href='$_SERVER[PHP_SELF]?halaman=$i'>$i</a> ";
            } else {
                echo "<span class='current'>$i</span> ";
            }
        }
        // Link ke halaman berikutnya (Next)
        if ($halaman < $jmlhal) {
            $next = $halaman + 1;
            echo "<span class='prevnext'><a href='$_SERVER[PHP_SELF]?halaman=$next'>Next &gt;&gt;</a></span>";
        } else {
            echo "<span class='disabled'>Next &gt;&gt;</span>";
        }
        echo "</div>";
        echo "<p align='center'><font face='Verdana' color='black' size='1'><b>$jmldata</b> data</font></p>";
        mysqli_close($con);
        echo "</td></tr>";
    }
}
?>

</div>

<?php  
include("footer.php");
?>
