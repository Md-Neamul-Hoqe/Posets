<?php
include "db_conn.php";

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = (int)$_GET['id'];
    $query = "SELECT * FROM db_posets_table WHERE idx = '$id'";
    $result = mysqli_query($conn, $query) or die("<span style='color: yellow'>No Such Index Exists. Query Error: SELECT. Error Code: " . mysqli_errno($conn) . ': ' . mysqli_error($conn) . '</span>');
    $query = "DELETE FROM db_posets_table WHERE idx = '$id'";
    $result = mysqli_query($conn, $query) or die("<span style='color: yellow'>Query Failed: DELETE. Error Code: " . mysqli_errno($conn) . ': ' . mysqli_error($conn) . '</span>');
    // how to update idx dynamically
    // DELETE FROM db_posets_table WHERE `db_posets_table`.`idx` = 8; UPDATE `db_posets_table` SET `idx` = '6' WHERE `db_posets_table`.`idx` = 8
}
header("Location: table.php");
exit();

