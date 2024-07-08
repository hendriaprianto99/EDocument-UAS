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
    $q = "SELECT * FROM tw_hak_akses WHERE tabel='jenis_pengguna' AND user = '" . $_SESSION['Email'] . "' AND listData='1'";
    $r = mysqli_query($con, $q);
    if ($obj = mysqli_fetch_object($r)) {
        ?>
        <br>
        <font face="Verdana" color="black" size="1">jenis_pengguna</font><br><br>
        <a href="insertjenis_pengguna.php">
            <button type="button" class="btn btn-light">
                <font face="Verdana" color="black" size="1"><i class="fa fa-plus"></i>&nbsp;Insert</font>
            </button>
        </a>
        &nbsp;&nbsp;
        <a href="printjenis_pengguna.php" target="_blank">
            <button type="button" class="btn btn-light">
                <font face="Verdana" color="black" size="1"><i class="fa fa-print"></i>&nbsp;Print</font>
            </button>
        </a>

        <!-- cari tabel -->
        <br><br>
        <form action="listjenis_pengguna.php" method="post">
            <select class="form-control" name="select">
                <?php
                $menu = mysqli_query($con, "SHOW COLUMNS FROM jenis_pengguna");
                while ($rowmenu = mysqli_fetch_array($menu)) {
                    echo "<option value=" . $rowmenu['Field'] . ">" . $rowmenu['Field'] . "</option>";
                }
                ?>
            </select>
            <input type="text" class="form-control" name="cari">
            <button type="submit" class="btn btn-success">
                <font face="Verdana" size="1"><i class="fa fa-search-plus"></i>Search</font>
            </button>
        </form><br>

        <?php
        if (isset($_POST["cari"]) && ($_POST["cari"] != "")) {
            $cari = mysqli_real_escape_string($con, $_POST["cari"]);
            $dd = "SELECT * FROM jenis_pengguna WHERE " . $_POST["select"] . " LIKE '%" . $cari . "%'";
            $resultcari = mysqli_query($con, $dd);
            if ($obj = mysqli_fetch_object($resultcari)) {
                $result = mysqli_query($con, $dd);
                ?>
                <font face="Verdana" color="black" size="1">Hasil Pencarian</font>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr bgcolor="D3DCE3">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><font face="Verdana" color="black" size="1">Jenis_Pengguna</font></th>
                            <th><font face="Verdana" color="black" size="1">Keterangan</font></th>
                        </tr>
                        <?php
                        $warna = 0;
                        while ($row = mysqli_fetch_array($result)) {
                            $bgColor = ($warna == 0) ? "E5E5E5" : "D5D5D5";
                            echo "<tr bgcolor='$bgColor' onMouseOver=\"this.bgColor='#8888FF';\" onMouseOut=\"this.bgColor='$bgColor';\">";
                            echo "<td><a class='linklist' href='viewjenis_pengguna.php?Jenis_Pengguna=" . $row['Jenis_Pengguna'] . "'><button type='button' class='btn btn-warning'><font face='Verdana' size='1'><i class='fa fa-check'></i></font></button></a></td>";
                            echo "<td><a class='linklist' href='editjenis_pengguna.php?Jenis_Pengguna=" . $row['Jenis_Pengguna'] . "'><button type='button' class='btn btn-primary'><font face='Verdana' size='1'><i class='fa fa-edit'></i></font></button></a></td>";
                            echo "<td><a class='linklist' href='deletejenis_pengguna.php?Jenis_Pengguna=" . $row['Jenis_Pengguna'] . "' onclick=\"return confirm('Are you sure you want to delete this data?')\"><button type='button' class='btn btn-danger'><font face='Verdana' size='1'><i class='fa fa-trash'></i></font></button></a></td>";
                            echo "<td><font face='Verdana' color='black' size='1'>" . $row['Jenis_Pengguna'] . "</font></td>";
                            echo "<td><font face='Verdana' color='black' size='1'>" . $row['Keterangan'] . "</font></td>";
                            echo "</tr>";
                            $warna = ($warna == 0) ? 1 : 0;
                        }
                        ?>
                    </table><br><br>
                </div>
            <?php
            } else {
                echo "<font size='2' face='Verdana' color='#FF0000'>Data jenis_pengguna not found - try again!</font><br><br>";
            }
        }
        ?>

        <?php
        if ((!isset($_POST["cari"])) or ($_POST["cari"] == "")) {
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
            $result = mysqli_query($con, "SELECT * FROM jenis_pengguna LIMIT $posisi,$batas");
            ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <?php
                    $firstColumn = 1;
                    $warna = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        if ($firstColumn == 1) {
                            ?>
                            <tr bgcolor="D3DCE3">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><font face="Verdana" color="black" size="1">Jenis_Pengguna</font></th>
                                <th><font face="Verdana" color="black" size="1">Keterangan</font></th>
                            </tr>
                            <?php
                            $firstColumn = 0;
                        }
                        $bgColor = ($warna == 0) ? "E5E5E5" : "D5D5D5";
                        echo "<tr bgcolor='$bgColor' onMouseOver=\"this.bgColor='#8888FF';\" onMouseOut=\"this.bgColor='$bgColor';\">";
                        echo "<td><a class='linklist' href='viewjenis_pengguna.php?Jenis_Pengguna=" . $row['Jenis_Pengguna'] . "'><button type='button' class='btn btn-warning'><font face='Verdana' size='1'><i class='fa fa-check'></i></font></button></a></td>";
                        echo "<td><a class='linklist' href='editjenis_pengguna.php?Jenis_Pengguna=" . $row['Jenis_Pengguna'] . "'><button type='button' class='btn btn-primary'><font face='Verdana' size='1'><i class='fa fa-edit'></i></font></button></a></td>";
                        echo "<td><a class='linklist' href='deletejenis_pengguna.php?Jenis_Pengguna=" . $row['Jenis_Pengguna'] . "' onclick=\"return confirm('Are you sure you want to delete this data?')\"><button type='button' class='btn btn-danger'><font face='Verdana' size='1'><i class='fa fa-trash'></i></font></button></a></td>";
                        echo "<td><font face='Verdana' color='black' size='1'>" . $row['Jenis_Pengguna'] . "</font></td>";
                        echo "<td><font face='Verdana' color='black' size='1'>" . $row['Keterangan'] . "</font></td>";
                        echo "</tr>";
                        $warna = ($warna == 0) ? 1 : 0;
                    }
                    ?>
                </table><br>
            </div>
            <?php
            //Langkah 3: Hitung total data dan halaman
            $tampil2 = mysqli_query($con, "SELECT * FROM jenis_pengguna");
            $jmldata = mysqli_num_rows($tampil2);
            $jmlhal  = ceil($jmldata / $batas);
            ?>
            <div class="paging">
                <?php
                // Link ke halaman sebelumnya (previous)
                if ($halaman > 1) {
                    $prev = $halaman - 1;
                    echo "<span class='prevnext'><a href='$_SERVER[PHP_SELF]?halaman=$prev'><font face='Verdana' color='black' size='1'><< Prev</font></a></span> ";
                } else {
                    echo "<span class='disabled'><font face='Verdana' color='black' size='1'><< Prev</font></span> ";
                }
                // Tampilkan link halaman 1,2,3 ...
                for ($i = 1; $i <= $jmlhal; $i++)
                    if ($i != $halaman) {
                        echo " <a href='$_SERVER[PHP_SELF]?halaman=$i'><font face='Verdana' color='black' size='1'>$i</font></a> ";
                    } else {
                        echo " <span class='current'><font face='Verdana' color='black' size='1'>$i</font></span> ";
                    }
                // Link kehalaman berikutnya (Next)
                if ($halaman < $jmlhal) {
                    $next = $halaman + 1;
                    echo "<span class='prevnext'><a href='$_SERVER[PHP_SELF]?halaman=$next'><font face='Verdana' color='black' size='1'>Next >></font></a></span>";
                } else {
                    echo "<span class='disabled'><font face='Verdana' color='black' size='1'>Next >></font></span>";
                }
                ?>
            </div>
            <p align="center"><font face="Verdana" color="black" size="1"><b><?php echo $jmldata; ?></b> data</font></p>
        <?php
        }
        mysqli_close($con);
        echo "</td></tr>";
    } else {
        //header("Location:content.php");
    }
    ?>

</div>

<?php include("footer.php"); ?>
