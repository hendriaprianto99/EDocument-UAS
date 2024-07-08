<?php
session_start();
if(!isset($_SESSION["Email"])){
    header("location:index.php");
    exit(); // tambahkan exit() setelah header
}

include("db.php");
include("header.php");
include("menu.php");
?>

<div id="page-wrapper">
<?php
//cek otoritas
$q = "SELECT * FROM tw_hak_akses WHERE tabel='pengguna/dokumen' AND user = '". $_SESSION['Email'] ."' AND listData='1'";
$r = mysqli_query($con, $q);
if ($obj = @mysqli_fetch_object($r)) {
?>
    <br><font face="Verdana" color="black" size="1">pengguna</font><br>
    <br><a href="insertmasterpenggunadokumen.php"><button type="button" class="btn btn-light"><font face="Verdana" color="black" size="1"><i class="fa fa-plus"></i>&nbsp;Insert</font></button></a><br>

    <!-- form pencarian -->
    <br><form action="listmasterpenggunadokumen.php" method="post">
        <select class="form-control" name="select">
            <?php
            $menu = mysqli_query($con, "SHOW COLUMNS FROM pengguna");
            while ($rowmenu = mysqli_fetch_array($menu)) {
                echo "<option value='". $rowmenu['Field'] ."'>". $rowmenu['Field'] ."</option>";
            }
            ?>
        </select>
        <input type="text" class="form-control" name="cari">
        <button type="submit" class="btn btn-success"><font face="Verdana" size="1"><i class="fa fa-search-plus"></i>Search</font></button>
    </form><br><br>

    <?php
    if (isset($_POST["cari"]) && ($_POST["cari"] != "")){
        // hasil pencarian tabel
        $dd = "SELECT * FROM pengguna WHERE ". $_POST["select"] ." LIKE '%" . $_POST["cari"] . "%'";
        $resultcari = mysqli_query($con, $dd);
        if ($obj = mysqli_fetch_object($resultcari)) {
            $result = mysqli_query($con, $dd);
            echo "<font face='Verdana' color='black' size='1'>Hasil Pencarian</font>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-striped'>";
            // tampilkan data hasil pencarian
            while ($row = mysqli_fetch_array($result)) {
                // tampilkan data tiap baris
                if ($warna == 0){
                    echo "<tr bgcolor='E5E5E5' onMouseOver=\"this.bgColor='#8888FF';\" onMouseOut=\"this.bgColor='E5E5E5';\">";
                    $warna = 1;
                }else{
                    echo "<tr bgcolor='D5D5D5' onMouseOver=\"this.bgColor='#8888FF';\" onMouseOut=\"this.bgColor='D5D5D5';\">";
                    $warna = 0;
                }
                echo "<td><a class='linklist' href='listmasterpenggunadokumendetail.php?Kode_Pengguna=".$row['Kode_Pengguna']."'><font face='Verdana' color='black' size='1'>Detail</font></a></td>";
                echo "<td><a class='linklist' href='viewmasterpenggunadokumen.php?Kode_Pengguna=".$row['Kode_Pengguna']."'><button type='button' class='btn btn-warning'><font face='Verdana' size='1'><i class='fa fa-check'></i></font></button></a></td>";
                echo "<td><a class='linklist' href='editmasterpenggunadokumen.php?Kode_Pengguna=".$row['Kode_Pengguna']."'><button type='button' class='btn btn-primary'><font face='Verdana' size='1'><i class='fa fa-edit'></i></font></button></a></td>";
                echo "<td><a class='linklist' href='deletemasterpenggunadokumen.php?Kode_Pengguna=".$row['Kode_Pengguna']."' onclick=\"return confirm('Are you sure you want to delete this data?')\"><button type='button' class='btn btn-danger'><font face='Verdana' size='1'><i class='fa fa-trash'></i></font></button></a></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['Kode_Pengguna'] . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['Nama'] . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['Jenis_Pengguna'] . "<br>";
                $l = mysqli_query($con, "select Keterangan from jenis_pengguna where Jenis_Pengguna = '". $row['Jenis_Pengguna'] ."'"); 
                while($rl = mysqli_fetch_array($l)){  
                    echo $rl[0];    
                } 
                echo "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['Email'] . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'>" . $row['Telepon'] . "</font></td>";
                echo "<td><font face='Verdana' color='black' size='1'><a href='images/" . $row['Foto'] . "' target='_blank'><img src='images/" . $row['Foto'] . "' width='50' height='50'></a></font></td>";
                echo "</tr>";
            }
            echo "</table><br><br>";
            echo "</div>";
        } else {
            echo "<font size='2' face='Verdana' color='#FF0000'>Data pengguna not found - try again!</font><br><br>";
        }
    }
    ?>
    <!-- end form pencarian -->

<?php
} else {
    //header("Location:content.php");
}
?>
</div>

<?php
include("footer.php");
?>
