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
    $q = "SELECT * FROM tw_hak_akses where tabel='pengguna' and user = '" . $_SESSION['Email'] . "' and editData='1'";
    $r = mysqli_query($con, $q);
    if ($obj = @mysqli_fetch_object($r)) {
    ?>
        <link href="standar.css" rel="stylesheet" type="text/css">

        <!-- calendar -->
        <script src="php_calendar/scripts.js" type="text/javascript"></script>
        <!-- TinyMCE -->
        <script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
        <script type="text/javascript">
            // script TinyMCE
        </script>
        <!-- /TinyMCE -->

        <?php
        echo "<td bgcolor=F5F5F5 valign=top>";
        echo "<table class='table table-striped'>";
        echo "<tr><td colspan=2><font face=Verdana color=black size=1>pengguna</font></td></tr>";
        echo "<form action=editpenggunaexec.php method=post>";
        $result = mysqli_query($con, "SELECT * FROM pengguna where Kode_Pengguna = '" . mysqli_real_escape_string($con, $_GET['Kode_Pengguna']) . "'");
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr><td colspan=2><input type=hidden name=pk value='" . $row['Kode_Pengguna'] . "'></td></tr>";
            echo "<tr><td bgcolor=CCCCCC><font face=Verdana color=black size=1>&nbsp;&nbsp;Kode_Pengguna&nbsp;&nbsp;</font></td>";
            echo "<td bgcolor=CCEEEE><font face=Verdana color=black size=1>&nbsp;&nbsp;" . $row['Kode_Pengguna'] . "&nbsp;&nbsp;</font></td>";
            echo "<tr><td bgcolor=CCCCCC><font face=Verdana color=black size=1>&nbsp;&nbsp;Kode_Pengguna&nbsp;&nbsp;</font></td>";
            echo "<td bgcolor=CCEEEE><input type=text  class='form-control' name='Kode_Pengguna' value='" . $row['Kode_Pengguna'] . "' size = 100></td>";
            echo "<tr><td bgcolor=CCCCCC><font face=Verdana color=black size=1>&nbsp;&nbsp;Nama&nbsp;&nbsp;</font></td>";
            echo "<td bgcolor=CCEEEE><input type=text  class='form-control' name='Nama' value='" . $row['Nama'] . "' size = 100></td>";
            echo "<tr><td bgcolor=CCCCCC><font face=Verdana color=black size=1>&nbsp;&nbsp;Jenis_Pengguna&nbsp;&nbsp;</font></td>";
            echo "<td bgcolor=CCEEEE>";
            echo "<select class='form-control' name='Jenis_Pengguna'>";
            $result = mysqli_query($con, "select * from jenis_pengguna");
            while ($r = mysqli_fetch_array($result)) {
                if ($r['Jenis_Pengguna'] == $row['Jenis_Pengguna']) {
                    echo "<option value='" . $r[Jenis_Pengguna] . "' selected>" . $r[Jenis_Pengguna] . " | " . $r[Keterangan] . "</option>";
                } else {
                    echo "<option value='" . $r[Jenis_Pengguna] . "'>" . $r[Jenis_Pengguna] . " | " . $r[Keterangan] . "</option>";
                }
            }
            echo "</select>";
            echo "</td>";
            echo "<tr><td bgcolor=CCCCCC><font face=Verdana color=black size=1>&nbsp;&nbsp;Email&nbsp;&nbsp;</font></td>";
            echo "<td bgcolor=CCEEEE><input type=text  class='form-control' name='Email' value='" . $row['Email'] . "' size = 100></td>";
            echo "<tr><td bgcolor=CCCCCC><font face=Verdana color=black size=1>&nbsp;&nbsp;Telepon&nbsp;&nbsp;</font></td>";
            echo "<td bgcolor=CCEEEE><input type=text  class='form-control' name='Telepon' value='" . $row['Telepon'] . "' size = 100></td>";
            echo "<tr><td bgcolor=CCCCCC><font face=Verdana color=black size=1>&nbsp;&nbsp;Foto&nbsp;&nbsp;</font></td>";
            if (isset($row['Kode_Pengguna'])) {
                echo "<td bgcolor=CCEEEE>";
                if (!empty($row['Foto'])) {
                    echo "<a href='images/" . $row['Foto'] . "' target=_blank><img src='images/" . $row['Foto'] . "' width=50 height=50></a><br>";
                    echo "<input type=text  class='form-control' name=Foto value=" . $row['Foto'] . " hidden>";
                }
                echo "<button><a href=uploadimagepengguna.php?Kode_Pengguna=" . $row['Kode_Pengguna'] . ">upload</a></button></td>";
            }
            echo "<tr><td colspan=2 align=center><button type='submit' class='btn btn-primary'><font face=Verdana size=1><i class='fa fa-edit'></i>&nbsp;Edit</font></button></td></tr>";
            echo "</form>";
            echo "</table></td></tr>";
        }
        ?>
    </div>

    <?php include("footer.php"); ?>
<?php
} else {
    //header("Location:content.php");
}
?>
