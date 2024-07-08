<?php
session_start();
include("db.php");
include("header.php");
include("menu.php");

if (!isset($_SESSION["Email"])) {
    header("location:index.php");
    exit; // Memastikan tidak ada kode yang dieksekusi setelah redirect
}

// Mengecek otoritas pengguna
$q = "SELECT * FROM tw_hak_akses WHERE tabel='dokumen/dokumen_file' AND user = ? AND viewData='1'";
$stmt = $con->prepare($q);
$stmt->bind_param("s", $_SESSION['Email']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    ?>
    <div id="page-wrapper">
        <div class='table-responsive'>
            <table class='table table-striped'>
                <tr>
                    <td colspan=2><font face=Verdana color=black size=1>dokumen</font></td>
                </tr>
                <?php
                $query = "SELECT * FROM dokumen WHERE No_Dokumen = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param("s", $_GET['No_Dokumen']);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td bgcolor=CCCCCC><font face=Verdana color=black size=1>No_Dokumen</font></td>
                        <td bgcolor=CCEEEE><font face=Verdana color=black size=1><?= $row['No_Dokumen'] ?></font></td>
                    </tr>
                    <tr>
                        <td bgcolor=CCCCCC><font face=Verdana color=black size=1>Kode</font></td>
                        <td bgcolor=CCEEEE>
                            <font face=Verdana color=black size=1><?= $row['Kode'] ?><br>
                                <?php
                                $l_query = "SELECT Kategori FROM kategori_dokumen WHERE Kode = ?";
                                $l_stmt = $con->prepare($l_query);
                                $l_stmt->bind_param("s", $row['Kode']);
                                $l_stmt->execute();
                                $l_result = $l_stmt->get_result();
                                while ($rl = $l_result->fetch_assoc()) {
                                    echo $rl['Kategori'];
                                }
                                ?>
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor=CCCCCC><font face=Verdana color=black size=1>Judul</font></td>
                        <td bgcolor=CCEEEE><font face=Verdana color=black size=1><?= $row['Judul'] ?></font></td>
                    </tr>
                    <tr>
                        <td bgcolor=CCCCCC><font face=Verdana color=black size=1>Deskripsi</font></td>
                        <td bgcolor=CCEEEE><font face=Verdana color=black size=1><?= $row['Deskripsi'] ?></font></td>
                    </tr>
                    <tr>
                        <td bgcolor=CCCCCC><font face=Verdana color=black size=1>Tanggal_Pembuatan</font></td>
                        <td bgcolor=CCEEEE><font face=Verdana color=black size=1><?= $row['Tanggal_Pembuatan'] ?></font></td>
                    </tr>
                    <tr>
                        <td bgcolor=CCCCCC><font face=Verdana color=black size=1>Tanggal_Modifikasi</font></td>
                        <td bgcolor=CCEEEE><font face=Verdana color=black size=1><?= $row['Tanggal_Modifikasi'] ?></font></td>
                    </tr>
                    <tr>
                        <td bgcolor=CCCCCC><font face=Verdana color=black size=1>Kode_Pengguna</font></td>
                        <td bgcolor=CCEEEE>
                            <font face=Verdana color=black size=1><?= $row['Kode_Pengguna'] ?><br>
                                <?php
                                $l_query = "SELECT Nama FROM pengguna WHERE Kode_Pengguna = ?";
                                $l_stmt = $con->prepare($l_query);
                                $l_stmt->bind_param("s", $row['Kode_Pengguna']);
                                $l_stmt->execute();
                                $l_result = $l_stmt->get_result();
                                while ($rl = $l_result->fetch_assoc()) {
                                    echo $rl['Nama'];
                                }
                                ?>
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 align=center>
                            <a href=listmasterdokumendokumen_file.php><button type='button' class='btn btn-warning'>
                                    <font face=Verdana size=1><i class='fa fa-check'></i>&nbsp;Back</font>
                                </button></a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <?php
    tulislog("view dokumen", $con);
} else {
    //header("Location:content.php");
}
include("footer.php");
?>
