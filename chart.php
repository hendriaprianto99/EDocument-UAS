<?php
// $con = mysqli_connect("localhost","root","","toko");

$excluded_tables = ["user", "tw_tabel", "tw_hak_akses", "logtw", "setting"];
$colors = ["red", "green", "blue", "orange", "brown"];

$txt = [];
$jml = [];
$warna = [];

$h = mysqli_query($con, "SHOW TABLES FROM edocument");
while ($r = mysqli_fetch_array($h)) {
    if (!in_array($r[0], $excluded_tables)) {
        $txt1 = $r[0];
        $x = mysqli_query($con, "SELECT COUNT(*) FROM ".$r[0]); // Menghitung jumlah data
        $row = mysqli_fetch_array($x);
        $jml1 = $row[0]; // Mengambil jumlah data dari hasil query
        if ($jml1 >= 0) { // Hanya tambahkan data jika jumlah data tidak nol
            $txt[] = $txt1;
            $jml[] = $jml1;
            $warna[] = $colors[count($jml) % count($colors)]; // Loop warna jika lebih banyak tabel
        }
    }
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<canvas id="myChart" style="width:100%;max-width:100%"></canvas>

<script>
    var xValues = <?php echo json_encode($txt); ?>;
    var yValues = <?php echo json_encode($jml); ?>;
    var barColors = <?php echo json_encode($warna); ?>;

    new Chart("myChart", {
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: barColors,
                data: yValues
            }]
        },
        options: {
            legend: { display: false },
            title: {
                display: true,
                text: "Jumlah Data"
            }
        }
    });
</script>
