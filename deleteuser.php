<?php
session_start();
if(!isset($_SESSION["Email"])){
    header("location:index.php");
    exit; // Exit setelah header untuk menghentikan eksekusi lebih lanjut
}
?>
<?php include("db.php"); ?>

<?php
// cek otoritas
$q = "SELECT * FROM tw_hak_akses WHERE tabel='user' AND user = '". $_SESSION['Email'] ."' AND deleteData='1'";
$r = mysqli_query($con, $q);
if ($obj = mysqli_fetch_object($r))
{
    include("tulislog.php");
    $Email = mysqli_real_escape_string($con, $_REQUEST['Email']); // Perbaikan: tambahkan tanda kutip pada 'Email'
    $result = mysqli_query($con, "DELETE FROM user WHERE Email = '". $Email . "'");
    tulislog("delete from user", $con); 
    header("Location:listuser.php");
    mysqli_close($con);
    exit; // Exit setelah header untuk menghentikan eksekusi lebih lanjut
}
else {
    header("Location:content.php");
    exit; // Exit setelah header untuk menghentikan eksekusi lebih lanjut
}
?>
