<?php
session_start();
if (!isset($_SESSION["Email"])) {
    header("location:index.php");
    exit; // Keluar dari script setelah melakukan redirect
}

include("db.php");
include("header.php");
include("menu.php");
include("tulislog.php");
?>

<div id="page-wrapper">
    <?php
    // Cek otoritas
    $q = "SELECT * FROM tw_hak_akses where tabel='kategori_dokumen/dokumen' and user = '" . $_SESSION['Email'] . "' and viewData='1'";
    $r = mysqli_query($con, $q);
    if ($obj = mysqli_fetch_object($r)) {
        ?>
        <div class='table-responsive'>
            <table class='table table-striped'>
                <tr>
                    <td colspan=2>
                        <font face=Verdana color=black size=1>kategori_dokumen</font>
                    </td>
                </tr>
                <?php
                $result = mysqli_query($con, "SELECT * FROM kategori_dokumen where Kode = '" . $_GET['Kode'] . "'");
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tr>
                        <td bgcolor=CCCCCC>
                            <font face=Verdana color=black size=1>Kode</font>
                        </td>
                        <td bgcolor=CCEEEE>
                            <font face=Verdana color=black size=1><?php echo $row['Kode']; ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor=CCCCCC>
                            <font face=Verdana color=black size=1>Kategori</font>
                        </td>
                        <td bgcolor=CCEEEE>
                            <font face=Verdana color=black size=1><?php echo $row['Kategori']; ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor=CCCCCC>
                            <font face=Verdana color=black size=1>Keterangan</font>
                        </td>
                        <td bgcolor=CCEEEE>
                            <font face=Verdana color=black size=1><?php echo $row['Keterangan']; ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 align=center>
                            <a href=listmasterkategori_dokumendokumen.php>
                                <button type='button' class='btn btn-warning'>
                                    <font face=Verdana size=1><i class='fa fa-check'></i>&nbsp;Back</font>
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <?php
        tulislog("view kategori_dokumen", $con);
    } else {
        //header("Location:content.php");
    }
    ?>
</div>

<?php
include("footer.php");
?>
