<?php
session_start();
if (!isset($_SESSION["Email"])) {
    header("location:index.php");
    exit; // keluar setelah redirect header
}
?>
<?php include("db.php"); ?>
<?php
// cek otoritas
$q = "SELECT * FROM tw_hak_akses WHERE tabel='Manage_User_Access' AND user = '" . $_SESSION['Email'] . "' AND deleteData='1'";
$r = mysqli_query($con, $q);
if ($obj = @mysqli_fetch_object($r)) {
    ?>
    <?php
    $tabel = mysqli_real_escape_string($con, $_REQUEST['tabel']); // tambahkan tanda kutip pada 'tabel'
    $result = mysqli_query($con, "DELETE FROM tw_tabel WHERE tabel = '" . $tabel . "'");
    header("Location:listmastertw_tabeltw_hak_akses.php");
    mysqli_close($con);
    ?>
    <?php
} else {
    header("Location:content.php");
    exit; // keluar setelah redirect header
}
?>
