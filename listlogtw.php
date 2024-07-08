<?php
session_start();
if (!isset($_SESSION["Email"])) {
    header("location:index.php");
    exit; // tambahkan exit setelah header
}

include("db.php");
include("header.php");
include("menu.php");
?>

<div id="page-wrapper">
    <?php
    //cek otoritas
    $q = "SELECT * FROM tw_hak_akses WHERE tabel='logtw' AND user = '" . $_SESSION['Email'] . "' AND listData='1'";
    $r = mysqli_query($con, $q);
    if ($obj = mysqli_fetch_object($r)) {
    ?>
        <br>
        <font face="Verdana" color="black" size="1">logtw</font><br><br>
        <a href="insertlogtw.php">
            <button type="button" class="btn btn-light">
                <font face="Verdana" color="black" size="1"><i class="fa fa-plus"></i>&nbsp;Insert</font>
            </button>
        </a>
        &nbsp;&nbsp;
        <a href="printlogtw.php" target="_blank">
            <button type="button" class="btn btn-light">
                <font face="Verdana" color="black" size="1"><i class="fa fa-print"></i>&nbsp;Print</font>
            </button>
        </a>
        <br><br>
        <form action="listlogtw.php" method="post">
            <select class="form-control" name="select">
                <?php
                $menu = mysqli_query($con, "SHOW COLUMNS FROM logtw");
                while ($rowmenu = mysqli_fetch_array($menu)) {
                    echo "<option value='" . $rowmenu['Field'] . "'>" . $rowmenu['Field'] . "</option>";
                }
                ?>
            </select>
            <input type="text" class="form-control" name="cari">
            <button type="submit" class="btn btn-success"><font face="Verdana" size="1"><i class="fa fa-search-plus"></i>Search</font></button>
        </form>
        <br>
        <?php
        if (isset($_POST["cari"]) && $_POST["cari"] != "") {
            $cari = mysqli_real_escape_string($con, $_POST["cari"]);
            //hasil pencarian tabel
            $dd = "SELECT * FROM logtw WHERE " . $_POST["select"] . " LIKE '%" . $cari . "%'";
            $resultcari = mysqli_query($con, $dd);
            if ($obj = mysqli_fetch_object($resultcari)) {
                $result = mysqli_query($con, $dd);
                echo "<font face='Verdana' color='black' size='1'>Hasil Pencarian</font>";
                echo "<div class='table-responsive'>";
                echo "<table class='table table-striped'>";
                echo "<tr bgcolor='D3DCE3'>";
                echo "<th></th><th></th><th></th><th><font face='Verdana' color='black' size='1'>id</font></th><th><font face='Verdana' color='black' size='1'>Time</font></th><th><font face='Verdana' color='black' size='1'>User</font></th><th><font face='Verdana' color='black' size='1'>IpAddress</font></th><th><font face='Verdana' color='black' size='1'>Information</font></th>";
                echo "</tr>";
                $warna = 0;
                while ($row = mysqli_fetch_array($result)) {
                    $bgColor = $warna == 0 ? "E5E5E5" : "D5D5D5";
                    echo "<tr bgcolor='$bgColor' onMouseOver=\"this.bgColor='#8888FF';\" onMouseOut=\"this.bgColor='$bgColor';\">";
                    echo "<td><a class='linklist' href='viewlogtw.php?id=" . $row['id'] . "'><button type='button' class='btn btn-warning'><font face='Verdana' size='1'><i class='fa fa-check'></i></font></button></a></td>";
                    echo "<td><a class='linklist' href='editlogtw.php?id=" . $row['id'] . "'><button type='button' class='btn btn-primary'><font face='Verdana' size='1'><i class='fa fa-edit'></i></font></button></a></td>";
                    echo "<td><a class='linklist' href='deletelogtw.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this data?')\"><button type='button' class='btn btn-danger'><font face='Verdana' size='1'><i class='fa fa-trash'></i></font></button></a></td>";
                    echo "<td><font face='Verdana' color='black' size='1'>" . $row['id'] . "</font></td>";
                    echo "<td><font face='Verdana' color='black' size='1'>" . $row['Time'] . "</font></td>";
                    echo "<td><font face='Verdana' color='black' size='1'>" . $row['User'] . "</font></td>";
                    echo "<td><font face='Verdana' color='black' size='1'>" . $row['IpAddress'] . "</font></td>";
                    echo "<td><font face='Verdana' color='black' size='1'>" . $row['Information'] . "</font></td>";
                    echo "</tr>";
                    $warna = 1 - $warna;
                }
                echo "</table><br><br>";
                echo "</div>";
            } else {
                echo "<font size='2' face='Verdana' color='#FF0000'>Data logtw not found - try again!</font><br><br>";
            }
        }
        if (!isset($_POST["cari"]) || $_POST["cari"] == "") {
            // Langkah 1: Tentukan batas,cek halaman & posisi data
            $batas = 100;
            $halaman = isset($_GET["halaman"]) ? $_GET['halaman'] : 1;
            $posisi = empty($halaman) ? 0 : ($halaman - 1) * $batas;
            $result = mysqli_query($con, "SELECT * FROM logtw LIMIT $posisi,$batas");
            echo "<div class='table-responsive'>";
            echo "<table class='table table-striped'>";
            $firstColumn = 1;
            $warna = 0;
            while ($row = mysqli_fetch_array($result)) {
                if ($firstColumn == 1) {
                    echo "<tr bgcolor='D3DCE3'>";
                    echo "<th></th><th></th><th></th><th><font face='Verdana' color='black' size='1'>id</font></th><th><font face='Verdana' color='black' size='1'>Time</font></th><th><font face='Verdana' color='black' size='1'>User</font></th><th><font face='Verdana' color='black' size='1'>IpAddress</font></th><th><font face='Verdana' color='black' size='1'>Information</font></th>";
                    echo "</tr>";
                    $firstColumn = 0;
                }
                $bgColor = $warna == 0 ? "E5E5E5" : "D5D5D5";
                echo "<tr bgcolor='$bgColor' onMouseOver=\"this.bgColor='#8888FF';\" onMouseOut=\"this.bgColor='$bgColor';\">";
                echo "<td><a class='linklist' href='viewlogtw.php?id=" . $row['id'] . "'><button type='button' class='btn btn-warning'><font face='Verdana' size='1'><i class='fa fa-check'></i></font></button></a></td>";
                echo "<td><a class='linklist' href='editlogtw.php?id=" . $row['id'] . "'><button type='button' class='btn btn-primary'><font face='Verdana' size='1'><i class='fa fa-edit'></i></font></button></a></td>";
                echo "<td><a class='linklist' href='deletelogtw.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this data?')\"><button type='button' class='btn btn-danger'><font face='Verdana' size='1'><i class='fa fa-trash'></i></font></button></a></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['id'] . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['Time'] . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['User'] . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['IpAddress'] . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['Information'] . "</font></td>";
                echo "</tr>";
                $warna = 1 - $warna;
            }
            echo "</table><br>";
            echo "</div>";
            //Langkah 3: Hitung total data dan halaman
            $tampil2 = mysqli_query($con, "SELECT * FROM logtw");
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
            for ($i = 1; $i <= $jmlhal; $i++)
                echo $i != $halaman ? " <a href='$_SERVER[PHP_SELF]?halaman=$i'><font face='Verdana' color='black' size='1'>$i</font></a> " : " <span class='current'><font face='Verdana' color='black' size='1'>$i</font></span> ";
            // Link kehalaman berikutnya (Next)
            if ($halaman < $jmlhal) {
                $next = $halaman + 1;
                echo "<span class='prevnext'><a href='$_SERVER[PHP_SELF]?halaman=$next'><font face='Verdana' color='black' size='1'>Next &gt;&gt;</font></a></span>";
            } else {
                echo "<span class='disabled'><font face='Verdana' color='black' size='1'>Next &gt;&gt;</font></span>";
            }
            echo "</div>";
            echo "<p align='center'><font face='Verdana' color='black' size='1'><b>$jmldata</b> data</font></p>";
            mysqli_close($con);
            echo "</td></tr>";
        }
    } else {
        //header("Location:content.php");
    }
    ?>
</div>

<?php include("footer.php"); ?>
