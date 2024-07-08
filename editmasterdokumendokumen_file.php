<?php
session_start();
if (!isset($_SESSION["Email"])) {
    header("location:index.php");
    exit; // Memastikan tidak ada kode yang dieksekusi setelah redirect
}

include("db.php");
include("header.php");
include("menu.php");
include("tulislog.php");
?>

<div id="page-wrapper">
    <?php
    // Cek otoritas pengguna
    $q = "SELECT * FROM tw_hak_akses WHERE tabel='dokumen/dokumen_file' AND user = ? AND editData='1'";
    $stmt = $con->prepare($q);
    $stmt->bind_param("s", $_SESSION['Email']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        ?>
        <link href="standar.css" rel="stylesheet" type="text/css">
        <!-- calendar -->
        <script src="php_calendar/scripts.js" type="text/javascript"></script>
        <!-- TinyMCE -->
        <script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
        <script type="text/javascript">
            // Tinymce initialization script
            // ...

            function fileBrowserCallBack(field_name, url, type, win) {
                // File browser callback function
                // ...
            }
        </script>

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
                <form action="editmasterdokumendokumen_fileexec.php" method="post">
                    <input type="hidden" name="pk" value="<?= $row['No_Dokumen'] ?>">
                    <tr>
                        <td bgcolor=CCCCCC><font face=Verdana color=black size=1>&nbsp;&nbsp;No_Dokumen&nbsp;&nbsp;</font></td>
                        <td bgcolor=CCEEEE><input type="text" class='form-control' name='No_Dokumen' value='<?= $row['No_Dokumen'] ?>'></td>
                    </tr>
                    <tr>
                        <td bgcolor=CCCCCC><font face=Verdana color=black size=1>&nbsp;&nbsp;Kode&nbsp;&nbsp;</font></td>
                        <td bgcolor=CCEEEE>
                            <select class='form-control' name='Kode'>
                                <?php
                                $kategori_result = mysqli_query($con, "SELECT * FROM kategori_dokumen");
                                while ($kategori_row = mysqli_fetch_array($kategori_result)) {
                                    if ($kategori_row['Kode'] == $row['Kode']) {
                                        echo "<option value='" . $kategori_row['Kode'] . "' selected>" . $kategori_row['Kode'] . " | " . $kategori_row['Kategori'] . "</option>";
                                    } else {
                                        echo "<option value='" . $kategori_row['Kode'] . "'>" . $kategori_row['Kode'] . " | " . $kategori_row['Kategori'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <!-- tambahkan bagian HTML untuk input Judul, Deskripsi, Tanggal_Pembuatan, Tanggal_Modifikasi, dan Kode_Pengguna disini -->
                    <tr>
                        <!-- contoh -->
                        <td bgcolor=CCCCCC><font face=Verdana color=black size=1>&nbsp;&nbsp;Judul&nbsp;&nbsp;</font></td>
                        <td bgcolor=CCEEEE><input type="text" class='form-control' name='Judul' value='<?= $row['Judul'] ?>' size=100></td>
                    </tr>
                    <!-- lanjutkan untuk bagian lainnya -->

                    <tr>
                        <td colspan=2 align=center>
                            <button type='submit' class='btn btn-primary'><font face=Verdana size=1><i class='fa fa-edit'></i>&nbsp;Edit</font></button>
                        </td>
                    </tr>
                </form>
            <?php } ?>
        </table>
    <?php
    } else {
        //header("Location:content.php");
    }
    ?>
</div>

<?php
include("footer.php");
?>
