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
    $q = "SELECT * FROM tw_hak_akses WHERE tabel='dokumen_file' AND user = '" . $_SESSION['Email'] . "' AND listData='1'";
    $r = mysqli_query($con, $q);

    if ($obj = mysqli_fetch_object($r)) {
        ?>
        <br>
        <font face="Verdana" color="black" size="1">dokumen_file</font>
        <br><br>
        <a href="insertdokumen_file.php">
            <button type="button" class="btn btn-light"><font face="Verdana" color="black" size="1"><i class="fa fa-plus"></i>&nbsp;Insert</font></button>
        </a>
        &nbsp;&nbsp;
        <a href="printdokumen_file.php" target="_blank">
            <button type="button" class="btn btn-light"><font face="Verdana" color="black" size="1"><i class="fa fa-print"></i>&nbsp;Print</font></button>
        </a>
        <br><br>
        <form action="listdokumen_file.php" method="post">
            <select class="form-control" name="select">
                <?php
                $menu = mysqli_query($con, "SHOW COLUMNS FROM dokumen_file");
                while ($rowmenu = mysqli_fetch_array($menu)) {
                    echo "<option value=\"" . $rowmenu['Field'] . "\">" . $rowmenu['Field'] . "</option>";
                }
                ?>
            </select>
            <input type="text" class="form-control" name="cari">
            <button type="submit" class="btn btn-success"><font face="Verdana" size="1"><i class="fa fa-search-plus"></i>Search</font></button>
        </form>
        <br>

        <?php
        if (isset($_POST["cari"]) && ($_POST["cari"] != "")) {
            $cari = mysqli_real_escape_string($con, $_POST["cari"]);
            $dd = "SELECT * FROM dokumen_file WHERE " . $_POST["select"] . " LIKE '%" . $cari . "%'";
            $resultcari = mysqli_query($con, $dd);
            if ($obj = mysqli_fetch_object($resultcari)) {
                ?>
                <font face="Verdana" color="black" size="1">Hasil Pencarian</font>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr bgcolor="D3DCE3">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><font face="Verdana" color="black" size="1">id</font></th>
                            <th><font face="Verdana" color="black" size="1">No_Dokumen</font></th>
                            <th><font face="Verdana" color="black" size="1">File</font></th>
                        </tr>
                        <?php
                        $warna = 0;
                        while ($row = mysqli_fetch_array($resultcari)) {
                            $warna = ($warna == 0) ? 1 : 0;
                            $bgColor = ($warna == 0) ? "E5E5E5" : "D5D5D5";
                            ?>
                            <tr bgcolor="<?php echo $bgColor; ?>" onMouseOver="this.bgColor='#8888FF';" onMouseOut="this.bgColor='<?php echo $bgColor; ?>';">
                                <td><a class="linklist" href="viewdokumen_file.php?id=<?php echo $row['id']; ?>"><button type="button" class="btn btn-warning"><font face="Verdana" size="1"><i class="fa fa-check"></i></font></button></a></td>
                                <td><a class="linklist" href="editdokumen_file.php?id=<?php echo $row['id']; ?>"><button type="button" class="btn btn-primary"><font face="Verdana" size="1"><i class="fa fa-edit"></i></font></button></a></td>
                                <td><a class="linklist" href="deletedokumen_file.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this data?')"><button type="button" class="btn btn-danger"><font face="Verdana" size="1"><i class="fa fa-trash"></i></font></button></a></td>
                                <td><font face="Verdana" color="black" size="1"><?php echo $row['id']; ?></font></td>
                                <td>
                                    <font face="Verdana" color="black" size="1"><?php echo $row['No_Dokumen']; ?><br>
                                    <?php
                                    $l = mysqli_query($con, "SELECT Judul FROM dokumen WHERE No_Dokumen = '" . $row['No_Dokumen'] . "'");
                                    while ($rl = mysqli_fetch_array($l)) {
                                        echo $rl[0];
                                    }
                                    ?>
                                    </font>
                                </td>
                                <td><font face="Verdana" color="black" size="1"><a href="files/<?php echo $row['File']; ?>" target="_blank"><?php echo $row['File']; ?></a></font></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            <?php } else {
                echo "<font size='2' face='Verdana' color='#FF0000'>Data dokumen_file not found - try again!</font><br><br>";
            }
        } else {
            // Langkah 1: Tentukan batas,cek halaman & posisi data
            $batas = 100;
            $halaman = isset($_GET["halaman"]) ? $_GET['halaman'] : 1;
            $posisi = ($halaman - 1) * $batas;
            $result = mysqli_query($con, "SELECT * FROM dokumen_file LIMIT $posisi,$batas");
            ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <tr bgcolor="D3DCE3">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><font face="Verdana" color="black" size="1">id</font></th>
                        <th><font face="Verdana" color="black" size="1">No_Dokumen</font></th>
                        <th><font face="Verdana" color="black" size="1">File</font></th>
                    </tr>
                    <?php
                    $warna = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        if ($warna == 0) {
                            $bgColor = "E5E5E5";
                            $warna = 1;
                        } else {
                            $bgColor = "D5D5D5";
                            $warna = 0;
                        }
                        ?>
                        <tr bgcolor="<?php echo $bgColor; ?>" onMouseOver="this.bgColor='#8888FF';" onMouseOut="this.bgColor='<?php echo $bgColor; ?>';">
                            <td><a class="linklist" href="viewdokumen_file.php?id=<?php echo $row['id']; ?>"><button type="button" class="btn btn-warning"><font face="Verdana" size="1"><i class="fa fa-check"></i></font></button></a></td>
                            <td><a class="linklist" href="editdokumen_file.php?id=<?php echo $row['id']; ?>"><button type="button" class="btn btn-primary"><font face="Verdana" size="1"><i class="fa fa-edit"></i></font></button></a></td>
                            <td><a class="linklist" href="deletedokumen_file.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this data?')"><button type="button" class="btn btn-danger"><font face="Verdana" size="1"><i class="fa fa-trash"></i></font></button></a></td>
                            <td><font face="Verdana" color="black" size="1"><?php echo $row['id']; ?></font></td>
                            <td>
                                <font face="Verdana" color="black" size="1"><?php echo $row['No_Dokumen']; ?><br>
                                    <?php
                                    $l = mysqli_query($con, "SELECT Judul FROM dokumen WHERE No_Dokumen = '" . $row['No_Dokumen'] . "'");
                                    while ($rl = mysqli_fetch_array($l)) {
                                        echo $rl[0];
                                    }
                                    ?>
                                </font>
                            </td>
                            <td><font face="Verdana" color="black" size="1"><a href="files/<?php echo $row['File']; ?>" target="_blank"><?php echo $row['File']; ?></a></font></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <?php
            //Langkah 3: Hitung total data dan halaman
            $tampil2 = mysqli_query($con, "SELECT * FROM dokumen_file");
            $jmldata = mysqli_num_rows($tampil2);
            $jmlhal  = ceil($jmldata / $batas);
            echo "<div class=paging>";
            // Link ke halaman sebelumnya (previous)
            if ($halaman > 1) {
                $prev = $halaman - 1;
                echo "<span class=prevnext><a href=$_SERVER[PHP_SELF]?halaman=$prev><font face=Verdana color=black size=1><< Prev</font></a></span> ";
            } else {
                echo "<span class=disabled><font face=Verdana color=black size=1><< Prev</font></span> ";
            }
            // Tampilkan link halaman 1,2,3 ...
            for ($i = 1; $i <= $jmlhal; $i++)
                echo ($i != $halaman) ? "<a href=$_SERVER[PHP_SELF]?halaman=$i><font face=Verdana color=black size=1>$i</font></a> " : "<span class=current><font face=Verdana color=black size=1>$i</font></span> ";
            // Link kehalaman berikutnya (Next)
            if ($halaman < $jmlhal) {
                $next = $halaman + 1;
                echo "<span class=prevnext><a href=$_SERVER[PHP_SELF]?halaman=$next><font face=Verdana color=black size=1>Next >></font></a></span>";
            } else {
                echo "<span class=disabled><font face=Verdana color=black size=1>Next >></font></span>";
            }
            echo "</div>";
            echo "<p align=center><font face=Verdana color=black size=1><b>$jmldata</b> data</font></p>";
            mysqli_close($con);
            echo "</td></tr>";
        }
    } else {
        //header("Location:content.php");
    }
    ?>
</div>

<?php include("footer.php"); ?>
