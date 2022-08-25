<?php
    $database = "reenduxs_rhdemo";
    if ($_GET['db']=='livedb') $database = "reenduxs_rhomes";
    $db = mysqli_connect("localhost", "root", "", $database);
    if (isset($_GET['id'])) {
        $sql = "SELECT kfo_foto, kfo_fototype FROM rms_kunfoto WHERE kfo_id='".$_GET['id']."'";
        $result = mysqli_query($db, $sql) or die("<b>Error:</b> Ada kesalahan<br/>".mysqli_error($db));
        $row = mysqli_fetch_array($result);
        header("Content-type: ".$row["kfo_fototype"]);
        echo $row["kfo_foto"];
    }
    mysqli_close($db);
?>