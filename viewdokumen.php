<?php
session_start();
if (!isset($_SESSION["Email"])) {
    header("location:index.php");
    exit; // tambahkan exit setelah header
}

include("db.php");
include("header.php");
include("menu.php");
include("tulislog.php");
?>

<div id="page-wrapper">
    <?php
    //cek otoritas
    $q = "SELECT * FROM tw_hak_akses WHERE tabel='dokumen' AND user = '" . $_SESSION['Email'] . "' AND viewData='1'";
    $r = mysqli_query($con, $q);

    if ($obj = mysqli_fetch_object($r)) {
        ?>
        <html>

        <head>
            <title>E-Document</title>
            <link rel="stylesheet" type="text/css" href="tag.css">
            <script type="text/javascript" src="tag.js"></script>
            <script type="text/javascript" src="kalender/calendar.js"></script>
            <link href="kalender/calendar.css" rel="stylesheet" type="text/css" media="screen">
        </head>

        <body topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 bgcolor=FFFFFF>
            <td bgcolor=F5F5F5 valign=top>
                <div class='table-responsive'>
                    <table class='table table-striped'>
                        <tr>
                            <td colspan=2><font face=Verdana color=black size=1>dokumen</font></td>
                        </tr>
                        <?php
                        $result = mysqli_query($con, "SELECT * FROM dokumen WHERE No_Dokumen = '" . mysqli_real_escape_string($con, $_GET['No_Dokumen']) . "'");
                        while ($row = mysqli_fetch_array($result)) {
                            ?>
                            <tr>
                                <td bgcolor=CCCCCC><font face=Verdana color=black size=1>No_Dokumen</font></td>
                                <td bgcolor=CCEEEE><font face=Verdana color=black size=1><?php echo $row['No_Dokumen']; ?></font></td>
                            </tr>
                            <tr>
                                <td bgcolor=CCCCCC><font face=Verdana color=black size=1>Kode</font></td>
                                <td bgcolor=CCEEEE><font face=Verdana color=black size=1><?php echo $row['Kode']; ?><br>
                                        <?php
                                        $l = mysqli_query($con, "SELECT Kategori FROM kategori_dokumen WHERE Kode = '" . $row['Kode'] . "'");
                                        while ($rl = mysqli_fetch_array($l)) {
                                            echo $rl[0];
                                        }
                                        ?>
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor=CCCCCC><font face=Verdana color=black size=1>Judul</font></td>
                                <td bgcolor=CCEEEE><font face=Verdana color=black size=1><?php echo $row['Judul']; ?></font></td>
                            </tr>
                            <tr>
                                <td bgcolor=CCCCCC><font face=Verdana color=black size=1>Deskripsi</font></td>
                                <td bgcolor=CCEEEE><font face=Verdana color=black size=1><?php echo $row['Deskripsi']; ?></font></td>
                            </tr>
                            <tr>
                                <td bgcolor=CCCCCC><font face=Verdana color=black size=1>Tanggal_Pembuatan</font></td>
                                <td bgcolor=CCEEEE><font face=Verdana color=black size=1><?php echo $row['Tanggal_Pembuatan']; ?></font></td>
                            </tr>
                            <tr>
                                <td bgcolor=CCCCCC><font face=Verdana color=black size=1>Tanggal_Modifikasi</font></td>
                                <td bgcolor=CCEEEE><font face=Verdana color=black size=1><?php echo $row['Tanggal_Modifikasi']; ?></font></td>
                            </tr>
                            <tr>
                                <td bgcolor=CCCCCC><font face=Verdana color=black size=1>Kode_Pengguna</font></td>
                                <td bgcolor=CCEEEE><font face=Verdana color=black size=1><?php echo $row['Kode_Pengguna']; ?><br>
                                        <?php
                                        $l = mysqli_query($con, "SELECT Nama FROM pengguna WHERE Kode_Pengguna = '" . $row['Kode_Pengguna'] . "'");
                                        while ($rl = mysqli_fetch_array($l)) {
                                            echo $rl[0];
                                        }
                                        ?>
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2 align=center><a href=listdokumen.php><button type='button' class='btn btn-warning'><font face=Verdana size=1><i class='fa fa-check'></i>&nbsp;Back</font></button></a></td>
                            </tr>
                        <?php } ?>
                    </table>
                    <br>
                </div>
                <?php tulislog("view dokumen", $con); ?>
            </td>
        </body>

        </html>
    <?php
    } else {
        //header("Location:content.php");
    }
    ?>

    <?php include("footer.php"); ?>
