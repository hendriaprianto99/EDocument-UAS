<?php
session_start();
if (!isset($_SESSION["Email"])) {
    header("location:index.php");
    exit;
}

include("db.php");
include("header.php");
include("menu.php");
?>

<div id="page-wrapper">
    <?php
    // Cek otoritas
    $q = "SELECT * FROM tw_hak_akses WHERE tabel='setting' AND user = '" . $_SESSION['Email'] . "' AND listData='1'";
    $r = mysqli_query($con, $q);
    if ($obj = mysqli_fetch_object($r)) :
    ?>
        <br>
        <font face="Verdana" color="black" size="1">setting</font>
        <br><br>
        <a href="insertsetting.php"><button type="button" class="btn btn-light"><font face="Verdana" color="black" size="1"><i class="fa fa-plus"></i>&nbsp;Insert</font></button></a>
        &nbsp;&nbsp;
        <a href="printsetting.php" target="_blank"><button type="button" class="btn btn-light"><font face="Verdana" color="black" size="1"><i class="fa fa-print"></i>&nbsp;Print</font></button></a>

        <br><br>
        <form action="listsetting.php" method="post">
            <select class="form-control" name="select">
                <?php
                $menu = mysqli_query($con, "SHOW COLUMNS FROM setting");
                while ($rowmenu = mysqli_fetch_array($menu)) {
                    echo "<option value='" . $rowmenu['Field'] . "'>" . $rowmenu['Field'] . "</option>";
                }
                ?>
            </select>
            <input type="text" class="form-control" name="cari">
            <button type="submit" class="btn btn-success"><font face="Verdana" size="1"><i class="fa fa-search-plus"></i>Search</font></button>
        </form>
        <br>

        <!-- Bagian Jenis Pengguna -->
        <?php
        $result_jenis_pengguna = mysqli_query($con, "SELECT * FROM jenis_pengguna");
        if (mysqli_num_rows($result_jenis_pengguna) > 0) {
            echo "<select class='form-control' name='Jenis_Pengguna'>";
            while ($row_jenis_pengguna = mysqli_fetch_array($result_jenis_pengguna)) {
                echo "<option value='" . $row_jenis_pengguna['Jenis_Pengguna'] . "'>" . $row_jenis_pengguna['Jenis_Pengguna'] . "</option>";
            }
            echo "</select>";
        } else {
            echo "Data Jenis Pengguna tidak ditemukan.";
        }
        ?>
    <?php endif; ?>
</div>

<?php include("footer.php"); ?>
