<?php include "db_conn.php";
?>
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
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="GET" class="form-control w-75 mx-auto">
                <label for="order">Enter The Order of The Matrix</label><input type="number" data-bs-toggle='tooltip' title="Enter The Order of The Matrix" name="order" id="Order" placeholder="<?php echo isset($_GET['search']) ? $_GET['order'] : 'Search by Matrix Order...' ?>">
                <input type="submit" name="search" value="Search . . ." placeholder="Search All Posets By Matrix Order." data-bs-toggle='tooltip' title="Search All Posets By Matrix Order.">
            </form>
            <br />
            <?php
            if (isset($_GET['search'])) {
                $order = $_GET['order'];
                $tableName = "allposets";
                $query = "SELECT * FROM $tableName WHERE MatrixOrder = $order";
                $result = mysqli_query($conn, $query) or die("<span class='error'> No " . $tableName . " Named Table Found. </span>");
                if (mysqli_num_rows($result) > 0) {
            ?>
                    <table id="searchedTable" class="mx-auto border-top border-3 border-danger mt-5 table-border">
                        <thead style="background-color: black; color: white;">
                            <th class="pb-1 fs-5 text-center bg-gradient bg-dark border border-dark" style="max-width: 300px;">Id</th>
                            <th class="pb-1 fs-5 text-center bg-gradient bg-dark border border-dark">Matrix Order</th>
                            <th class="pb-1 fs-5 text-center bg-gradient bg-dark border border-dark">Matrix</th>
                            <th class="pb-1 fs-5 text-center bg-gradient bg-dark border border-dark">Edit</th>
                            <th class="pb-1 fs-5 text-center bg-gradient bg-dark border border-dark">Delete</th>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr class="min-vw-100">
                                    <!-- Must be similar to the data base field name  -->
                                    <td class="border fs-5 text-center" style="min-width: 10px; text-align: center;"> <?php echo $row['idx'] ?> </td>
                                    <td class="border fs-5 text-center" style="min-width: 10px; text-align: center;"> <?php echo $row['MatrixOrder'] ?> </td>
                                    <td class="border fs-5 text-center" style="min-width: 10px; text-align: center;"> <?php echo "[" . $row['Matrix'] . "]" ?> </td>
                                    <td class="border fs-5 text-center" style="min-width: 10px; text-align: center;">
                                        <?php echo "<a class='delete bg-warning bg-gradient' href='edit.php?action=update&id=" . $row['idx'] . "&morder=" . $row['MatrixOrder'] . "'>Edit</a>"; ?>
                                    </td>
                                    <td class="border fs-5 text-center">
                                        <?php echo "<a class='delete' href='delete.php?action=delete&id=" . $row['idx'] . "'>Delete</a>"; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
            <?php } else {
                    echo "<span class='success-null'>No Matrix of Order $order Found in '$tableName'.</span>";
                }
            }

            mysqli_close($conn);
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