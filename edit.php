<?php include "db_conn.php"; ?>
<!-- Bootstrap 5.2  -->
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/css/bootstrap.min.css"> <!-- 5.2.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="./styles/css/addCss/style.css"> <!-- Custom Css Styles -->
    <link rel="shortcut icon" href="./styles/assets/images/favicon.ico" type="image/x-icon">
    <title>Poset-Matrices || Order Matrix</title>
</head>

<body>
    <!-- Styles Every HTML markups if possible. -->
    <header class="header-section">
        <!-- This is Header Part of the page  -->
        <?php include "menu.php"; ?>
    </header>
    <!-- This is content part of the page  -->
    <main class="content-wrapper py-3">
        <!-- Sub section of main contents -->
        <!-- ================= TOTAL POSETS-COUNT TABLE ============= -->
        <section id="totalTable-search-section" class="my-5 py-5 px-3">
            <!-- Input Form  -->
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" id="inputForm" class="form-control w-75 mx-auto">
                <table class="updateTable w-100">
                    <thead>
                        <tr>
                        <th colspan="2" class="text-center p-2 fs-4 border border-3 border-warning">
                                <?php
                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                    $id = (int)$_GET['id'];
                                    echo "Edit The Entry For ID = " . $id; ?>

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- Index automatic generated -->
                            <td><label for="morder">Enter The Order of The Matrix</label><input type="number"    data-bs-toggle='tooltip' title="Enter The Order of The Matrix" name="morder" id="POrder" placeholder="<?php echo isset($_GET['MOrder']) ? $_GET['MOrder'] : NULL; ?>"></td>
                        </tr>
                        <tr>
                            <td><label for="matrix">Enter the Matrix</label><pre><textarea name="matrix" id="Matrix" cols="30" rows="5" placeholder="<?php echo isset($_POST['matrix']) ? $_POST['matrix'] : ''; ?>"></textarea></pre></td>
                        </tr>
                        <tr>
                            <td>Enter the Matrix File</td>
                            <td>
                                <input type="file" name="mfile" id="MFile" value="<?php echo isset($_FILES['mfile']) ? $_FILES['mfile']['name'] : ''; ?>">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <input type="submit" value="Edit" name="update">
            </form>

            <!-- Show the updated values in this page -->
            <?php
                                    if (isset($_POST['update'])) {
                                        $MOrder = $_POST["morder"];
                                        $Matrix = $_POST['matrix']; ?>
                <div class="output">
                    <?php
                                        if (empty($MOrder) and empty($Matrix)) {
                                            $filename = $_FILES['mfile']['name'];
                                            if (isset($_FILES['mfile']) && !empty($filename)) {
                                                $Files = fopen($filename, 'r+') or die("<span style='color: red;'>No file exists.</span>");

                                                // $Files = fopen($filename, 'r+') or die("<span style='color: red;'>No file exists.</span>");
                                                $i = 0;
                                                while (!feof($Files)) {
                                                    echo fgets($Files) . "<br/>";
                                                    $i = $i + 1;
                                                }
                                                $MOrder = $i;
                                                // $Files = str_split($Files); "UPDATE 'db_posets_table' SET 'idx' = '$id', 'MatrixOrder' = '$MOrder', 'matrix' = '$Matrix' WHERE 1";
                                                $query = "UPDATE `db_posets_table` SET `idx` = '$id', `MatrixOrder` = '$MOrder', `matrix` = '$Matrix' WHERE `db_posets_table`.`idx` = '$id'";
                                                $run  = mysqli_query($conn, $query) or die("Data Update Failed: " . mysqli_error($conn));
                                                echo "The size of the matrix is " . $MOrder;
                                                fclose($Files);
                                            } else {
                                                echo "<span style='color: red;'>Fields must not be empty.</span>";
                                            }
                                        } else if (empty($MOrder) or empty($Matrix)) {
                                            echo "<span style='color: red;'>Please Input The Matrix with order or A File of the matrix.</span>";
                                        } else {
                                            $query = "UPDATE `db_posets_table` SET `idx` = '$id', `MatrixOrder` = '$MOrder', `matrix` = '$Matrix' WHERE `db_posets_table`.`idx` = '$id'";
                                            $run  = mysqli_query($conn, $query) or die("<span class='error' style='line-height: 200%;'>Data Update Failed: " . mysqli_error($conn));
                                            echo "<h2 style='color: green'>This matrix has been updated:</h2>";
                                            echo "The order of the matrix is " . $MOrder . "<br/>";
                                            str_split($Matrix, $MOrder);
                                            echo "<span style='margin-left: -100px;'>The matrix is </span>" . "<br/>" . $Matrix;
                                        } ?>
                </div>
        <?php
                                    }
                                }
        ?>
        </section>
        <script>
          /**
           *  Customized Basic Scripts [If Needed]
           */
          $("[data-bs-toggle='tooltip']").tooltip();
        const tooltipTriggerList = document.querySelectorAll("[data-bs-toggle='tooltip']");
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
     </script>
</body>

</html>