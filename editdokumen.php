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
    $q = "SELECT * FROM tw_hak_akses WHERE tabel='dokumen' AND user = '" . $_SESSION['Email'] . "' AND editData='1'";
    $r = mysqli_query($con, $q);

    if ($obj = mysqli_fetch_object($r)) {
        ?>
        <link href="standar.css" rel="stylesheet" type="text/css">
        <script src="php_calendar/scripts.js" type="text/javascript"></script>
        <script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
        <script type="text/javascript">
            // Script TinyMCE dan calendar di sini
        </script>

        <table class="table table-striped">
            <tr>
                <td colspan="2"><font face="Verdana" color="black" size="1">dokumen</font></td>
            </tr>
            <form action="editdokumenexec.php" method="post">
                <?php
                $result = mysqli_query($con, "SELECT * FROM dokumen WHERE No_Dokumen = '" . mysqli_real_escape_string($con, $_GET['No_Dokumen']) . "'");
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <input type="hidden" name="pk" value="<?php echo $row['No_Dokumen']; ?>">
                    <tr>
                        <td bgcolor="CCCCCC"><font face="Verdana" color="black" size="1">&nbsp;&nbsp;No_Dokumen&nbsp;&nbsp;</font></td>
                        <td bgcolor="CCEEEE"><font face="Verdana" color="black" size="1">&nbsp;&nbsp;<?php echo $row['No_Dokumen']; ?>&nbsp;&nbsp;</font></td>
                    </tr>
                    <tr>
                        <td bgcolor="CCCCCC"><font face="Verdana" color="black" size="1">&nbsp;&nbsp;Kode&nbsp;&nbsp;</font></td>
                        <td bgcolor="CCEEEE">
                            <select class="form-control" name="Kode">
                                <?php
                                $kategori_result = mysqli_query($con, "SELECT * FROM kategori_dokumen");
                                while ($kategori_row = mysqli_fetch_array($kategori_result)) {
                                    $selected = ($kategori_row['Kode'] == $row['Kode']) ? 'selected' : '';
                                    echo "<option value='" . $kategori_row['Kode'] . "' $selected>" . $kategori_row['Kode'] . " | " . $kategori_row['Kategori'] . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="CCCCCC"><font face="Verdana" color="black" size="1">&nbsp;&nbsp;Judul&nbsp;&nbsp;</font></td>
                        <td bgcolor="CCEEEE"><input type="text" class="form-control" name="Judul" value="<?php echo $row['Judul']; ?>" size="100"></td>
                    </tr>
                    <tr>
                        <td bgcolor="CCCCCC"><font face="Verdana" color="black" size="1">&nbsp;&nbsp;Deskripsi&nbsp;&nbsp;</font></td>
                        <td bgcolor="CCEEEE"><textarea class="form-control" name="Deskripsi"><?php echo $row['Deskripsi']; ?></textarea></td>
                    </tr>
                    <tr>
                        <td bgcolor="CCCCCC"><font face="Verdana" color="black" size="1">&nbsp;&nbsp;Tanggal_Pembuatan&nbsp;&nbsp;</font></td>
                        <td bgcolor="CCEEEE"><input type="text" id="Tanggal_Pembuatan" name="Tanggal_Pembuatan" value="<?php echo $row['Tanggal_Pembuatan']; ?>" size="10"><script type="text/javascript">calendar.set('Tanggal_Pembuatan');</script></td>
                    </tr>
                    <tr>
                        <td bgcolor="CCCCCC"><font face="Verdana" color="black" size="1">&nbsp;&nbsp;Tanggal_Modifikasi&nbsp;&nbsp;</font></td>
                        <td bgcolor="CCEEEE"><input type="text" id="Tanggal_Modifikasi" name="Tanggal_Modifikasi" value="<?php echo $row['Tanggal_Modifikasi']; ?>" size="10"><script type="text/javascript">calendar.set('Tanggal_Modifikasi');</script></td>
                    </tr>
                    <tr>
                        <td bgcolor="CCCCCC"><font face="Verdana" color="black" size="1">&nbsp;&nbsp;Kode_Pengguna&nbsp;&nbsp;</font></td>
                        <td bgcolor="CCEEEE">
                            <select class="form-control" name="Kode_Pengguna">
                                <?php
                                $pengguna_result = mysqli_query($con, "SELECT * FROM pengguna");
                                while ($pengguna_row = mysqli_fetch_array($pengguna_result)) {
                                    $selected = ($pengguna_row['Kode_Pengguna'] == $row['Kode_Pengguna']) ? 'selected' : '';
                                    echo "<option value='" . $pengguna_row['Kode_Pengguna'] . "' $selected>" . $pengguna_row['Kode_Pengguna'] . " | " . $pengguna_row['Nama'] . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><button type="submit" class="btn btn-primary"><font face="Verdana" size="1"><i class="fa fa-edit"></i>&nbsp;Edit</font></button></td>
                    </tr>
                <?php } ?>
            </form>
        </table>
    <?php
    } else {
        //header("Location:content.php");
    }
    ?>
</div>

<?php include("footer.php"); ?>
