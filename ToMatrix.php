<?php
include "header.php";

$MOrder = $_GET["Order"];
?>
<title>Search Posets</title>
</head>
<script>
    morder = <?= $MOrder; ?>;
    /* ========= Initialising The Variables =========== */
    var xCoord = 0,
        yCoord = 0,
        type = "Connected",
        PxCurrent = 0,
        PyCurrent = 0,
        PStart = -1,
        PEnd = -1,
        isHovered = false,
        keyPosY = 0,
        keyPosX = 0,
        Draw = 0,
        SELength = 0, // Array To Store The Number of Selected Nodes
        selectedElements = [], // Array To Store The Selected Nodes
        HEs = [],
        ConnectedTo = [],
        DistanceFromGrids = [], // Distance From Mesh Poits To Mouse Selection
        XYPoints = []; // Mesh Points with max info
    /* ========= Initialisation of Canvas =========== */
</script>

<body class="bg-white-50 text-dark">
    <!-- Styles Every HTML markups if possible. -->
    <header class="header-section">
        <!-- This is Header Part of the page  -->
        <?php include "menu.php"; ?>
    </header>

    <!-- Click to Back / Top Buttons  -->
    <a action="action" class="bg-warning bg-opacity-25 rounded-5" href='#' onclick="history.back()" value="Back" id="backPage"><img src="./styles/assets/images/backPage.png" alt="Back To Previous Page"></a>
    <a class="bg-success bg-opacity-25 rounded-5" href="#" id="myBtn"><img title='Poset-Matrix' src="./styles/assets/images/backToTop.png" alt="Back To Top"></a>

    <main class="content-wrapper pt-5 mt-5 min-vh-100">
        <!-- Draw The Poset Which is just Inputed to search -->
        <!-- <div class="text-center" data-bs-content="Draw The Hasse Diagram of The Poset Matrix.">
            <canvas id="poset" width="200" height="200" class="border border-4 border-dark shadow-lg p-0">
                Sorry! Canvas Is Not Supported In Your Browser. Please Search manually.
            </canvas>
        </div> -->
        <?php
        // echo "<pre>";
        // print_r($_GET);
        // echo "</pre>";

        // $MOrder = 5;
        /**
         * ####################################################
         * Search By Drawing Tool
         *  ###################################################
         */
        if (isset($_GET["matrix"]) && isset($_GET["SEs"])) {
            /* Get The Matrix From Drawing Tool */
            $Matrix = $_GET["matrix"];
            $SelectedElements = json_decode($_GET["SEs"]);
            // echo "<pre>";
            // print_r($SelectedElements);
            // echo "</pre>";

        ?>
            <script>
                selectedElements = <?= json_encode($SelectedElements); ?>;
                SELength = selectedElements.length;
                // console.table(selectedElements);
            </script>
            <!-- Draw The Poset Which is just Inputed to search -->
            <!-- <div class="text-center">
                <canvas id="poset" width="200" height="200" class="border border-4 border-dark shadow-lg p-0">
                    Sorry! Canvas Is Not Supported In Your Browser. Please Search manually.
                </canvas>
            </div> -->
            <?php
            // exit();
            $table = "allposets";
            $Posets = "The";        // Allposets = The Posets

            /* REMOVE ',' FROM GIVEN MATRIX To count size of the matrix */
            // $Elements = preg_split("/[,]+/", "$Matrix");
            $numOfElements = sizeof(preg_split("/[,]+/", "$Matrix")); /* Size of characteristics Matrix */

            /* TRANSFORM GIVEN MATRIX TO THE FORM OF DATABASE MATRIX */
            $ExtractElements = $Matrix;
            $k = 0;
            while ($k < 2 * $numOfElements - 1) {
                // echo $ExtractElements[$k];

                /* Replace ',' by space */
                if ($ExtractElements[$k] === ',') {
                    $ExtractElements[$k] = ' ';
                } else {
                    //  Save the elements of the poset Characteristic matrix
                    $ExtractElements[$k] = (int) $ExtractElements[$k];
                }
                $k++;
            }
            // $Matrix = trim($ExtractElements);
            $Matrix = $ExtractElements;
            // $rowR = trim($Matrix);
            // echo "Table: ", $table, " Height: ", $Height, " Width: ", $Width;
            // echo $table, $MOrder, $Matrix;

            $stmt_1 = "SELECT $table.`Matrix`, $table.`Height`, $table.`Width` FROM $table WHERE `Matrix` = '$Matrix'";
            $result = mysqli_query($conn, $stmt_1);
            ($result) ? ($num = mysqli_num_rows($result)) : die("<div class='error'>Error-" . mysqli_errno($conn) . ": Error - " . mysqli_error($conn) . "</div>");
            // $stmt_1 = "SELECT $table.`Matrix`, $table.`Height`, $table.`Width` FROM $table WHERE `MatrixOrder` = $MOrder AND `Matrix` = '$Matrix'";
            // echo $stmt_1;
            // $searchBoxShow = false;

            echo '<div id="searchedTable" class="mx-auto mt-5">';
            if ($num > 0) {
            ?>
                <div class="row">
                    <div class="col text-center fs-3">
                        <?php
                        echo "The best match for your search by &lt;'Drawing&nbsp;Tool'&gt;";
                        ?>
                    </div>
                </div>
                <div id="TableBody" class="row">
                    <div class="col text-center">
                        <!-- Show Every Poset's Matrix With it's Hasse Diagram -->
                        <?php
                        $row = mysqli_fetch_all($result);
                        // echo "<pre>";
                        // print_r($row[0]);
                        // echo "</pre>";
                        $Height = $row[0][1];
                        $Width = $row[0][2];
                        // echo $Max;
                        // while ($MatrixNo <= $Max) {
                        ?>
                        <div class="row">
                            <div class="col fs-5 mt-3">
                                <?php
                                /* Set Superscript on Number place */
                                // if ($MatrixNo % 10 == 1) {
                                //     $place = 'st';
                                // } else if ($MatrixNo % 10 == 2) {
                                //     $place = 'nd';
                                // } else if ($MatrixNo % 10 == 3) {
                                //     $place = 'rd';
                                // } else {
                                //     $place = 'th';
                                // }
                                // if(isset())
                                /* Header of The Poset */
                                // echo "<span class='fs-3'>The best match for your search by &lt;'Drawing Tool'&gt; </span><br>";
                                echo "<span class='fs-6'>The Height is ", (($Height > 0) ? $Height : 'Not Identified') . "&nbsp;&&nbsp;Width&nbsp;is&nbsp;" . (($Width > 0) ? $Width : 'Not Identified'), " of the poset.</span>";
                                // echo $MatrixNo . "<sup>" . $place . "</sup> Matrix <hr style='border: 2px solid; border-radius: 100%;'/>";

                                // echo $row[$MatrixNo - 1][0];
                                // MatrixConstruction($MOrder, $row[$MatrixNo - 1][0], $Posets);
                                MatrixConstruction($MOrder, $Height, $Width, $row[0][0], $Posets);

                                echo "<hr>";
                                ?>
                            </div>
                        </div>
                    </div>
                <?php
                // } // WHILE LOOP CLOSEED
            } else {
                echo "<div class='error mt-5'>Sorry! There have no such kind of poset exist.</div>";
                // echo "<div class='error mt-5'>No Posets Found For $MOrder Elements</div>";
            } // IF CONDITION END
                ?>
                </div>
                </div>
            <?php


            /**
             * ####################################################
             * Search Menually
             * ####################################################
             */
        } else if (isset($_GET["Height"]) && isset($_GET["Width"])) {

            // Catch The Order & Height From index.html throw by get method

            // $table = "allposets";
            // $table = "connposets";
            $table = $_GET["Table"];

            // $Posets = "All";
            $Posets = ($table == "connposets") ? "Connected" : (($table == "disconnposets") ? "Dis-Connected" : "All");

            /* Manually Searching Portion */
            $Height = $_GET["Height"];
            $Width = $_GET["Width"];

            // echo "Table: ", $table, " Height: ", $Height, " Width: ", $Width;

            // echo "Now you are searching the poset menually with Order: ", $MOrder;

            // $stmt_1 = "SELECT $table.Matrix FROM $table WHERE MatrixOrder = $MOrder AND Height = $Height AND Width = $Width ORDER BY $table.Matrix ASC";
            $stmt_1 = "SELECT $table.Matrix FROM $table WHERE MatrixOrder = $MOrder AND Height = $Height ORDER BY $table.Matrix ASC";
            // $searchBoxShow = true;
            $result = mysqli_query($conn, $stmt_1);
            $num = mysqli_num_rows($result);
            ?>
                <script>
                    /* Hide drawing tool in menual search */
                    document.getElementById("poset").hidden = true;
                </script>
                <!-- ############################################### 
                Search Interval 
                ############################################### -->

                <form id="IntervalForm" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" class="d-flex border-bottom border-5 border-dark mb-3 rounded-4">
                    <label for="min" hidden>Enter The Min Index No.</label>
                    <input id="Min" class="form-control me-2" placeholder="<?php echo isset($_POST['MIN']) ? $_POST['MIN'] : 'Search From 0'; ?>" type="search" name="MIN" id="searchMin">
                    <label for="max" hidden>Enter The Max Index No.</label>
                    <input id="Max" class="form-control" placeholder='<?php echo isset($_POST["MIN"]) ? $_POST["MAX"] : "Search Up To $num"; ?>' type="search" name="MAX" id="searchMax">
                    <input class="ms-2" type="submit" value="Search...">
                </form>
                <p class="bg-warning text-info bg-opacity-25 text-center rounded fs-6 fw-bolder py-2">Take A Short Range To Convenient Search</p>

                <!-- Show The Search Result In Matrix With Figure Form -->
                <?php

                echo '<section id="SearchResults">';
                // echo "<pre>";
                // print_r($_POST);
                // echo "</pre>";
                $Start = 1;
                $Interval = 10;
                // if (isset($_POST['submit']) || (isset($_POST['MIN']) or isset($_POST['MAX']))) {
                $MatrixNo = (isset($_POST['MIN']) and $_POST['MIN'] <> 0) ? $_POST['MIN'] : $Start;      /* Index Start From 1 */
                // $Max = (isset($_POST['MAX']) and $num >= $_POST['MAX']) ? $_POST['MAX'] : $num;
                $Max = (isset($_POST['MAX']) and $_POST['MAX'] <= $num) ? $_POST['MAX'] : (($num > $Interval) ? $Interval : $num); // Don't show all only first 30, if try to see all.
                // $Max = (isset($_POST['MAX']) and $_POST['MAX'] <= $num) ? $_POST['MAX'] : 30; // Don't show all only first 30, if try to see all.
                // echo $MatrixNo;
                // echo $Max;
                // echo $num;
                if ($num > 0) {
                ?>
                    <div id="searchedTable" class="mx-auto mt-5">
                        <div class="row">
                            <div class="col text-center fs-3">
                                <?php
                                echo "<h1 class='fs-3'>$Posets Poset-Matrices For $MOrder Elements";
                                echo (isset($Height)) ? " With&nbsp;Height&nbsp;" . $Height : '';
                                echo ((isset($Width)) ? "&nbsp;&&nbsp;Width&nbsp;" . $Width :  "</h1>");
                                ?>
                            </div>
                        </div>
                        <div id="TableBody" class="row">
                            <div class="col text-center">
                                <!-- Show Every Poset's Matrix With it's Hasse Diagram -->
                                <?php
                                $row = mysqli_fetch_all($result);
                                // echo "<pre>";
                                // print_r($row[0][0]);
                                // echo "</pre>";
                                // $Height = $row[0][1];
                                // $Width = $row[0][2];
                                // echo "The Height is ", (($Height > 0) ? $Height : 'Not Identified') . "&nbsp;&&nbsp;Width&nbsp;is&nbsp;" . (($Width > 0) ? $Width : 'Not Identified'), " of the poset.";
                                // echo $Max;
                                while ($MatrixNo <= $Max) {
                                ?>
                                    <div class="row">
                                        <div class="col fs-5 mt-3">
                                            <?php
                                            /* Set Superscript on Number place */
                                            if ($MatrixNo % 10 == 1) {
                                                $place = 'st';
                                            } else if ($MatrixNo % 10 == 2) {

                                                $place = 'nd';
                                            } else if ($MatrixNo % 10 == 3) {
                                                $place = 'rd';
                                            } else {
                                                $place = 'th';
                                            }
                                            /* Header of The Poset */
                                            // if(isset())
                                            // echo "<span class='fs-6'>The best match for your search by &lt;'Drawing Tool'&gt; </span><br>";
                                            echo $MatrixNo . "<sup>" . $place . "</sup> Matrix <hr style='border: 2px solid; border-radius: 100%;'/>";

                                            // echo $row[$MatrixNo - 1][0];
                                            // MatrixConstruction($MOrder, $row[$MatrixNo - 1][0], $Posets);
                                            MatrixConstruction($MOrder, $Height, $Width, $row[$MatrixNo - 1][0], $Posets);

                                            echo "<hr>";
                                            if ($MatrixNo == $Max and $Max < $num) {
                                            ?>
                                                <button onclick="SearchNext10()" class='border border-3 p-3 rounded'>Search Up To <?= $num ?><sup> <?= $place ?> </sup> Posets By Search Box.</button>
                                                <script>
                                                    /**
                                                     * Show Next 10 Posets On Click The Button
                                                     */
                                                    function SearchNext10() {
                                                        <?php
                                                        if (isset($_POST["Min"])) {
                                                            $_POST["Min"] = $MatrixNo;
                                                        }
                                                        echo isset($_POST["Max"]);
                                                        if (isset($_POST["Max"])) {
                                                            $_POST["Max"] = ($MatrixNo + $Interval > $num) ? $num : $MatrixNo + $Interval;
                                                        }
                                                        ?>
                                                        document.getElementById('Min').value = "<?php echo $MatrixNo ?>";
                                                        document.getElementById('Max').value = "<?php echo ($MatrixNo + $Interval > $num) ? $num : ($MatrixNo + $Interval)
                                                                                                ?>";

                                                        document.getElementById("IntervalForm").submit();
                                                    }
                                                </script>
                                            <?php
                                                if ($num < $Max) {
                                                    echo "<br><div class='border border-3 p-3 rounded'>No More Posets Found.</div>";
                                                }
                                            }
                                            // echo $MatrixNo;
                                            $MatrixNo++;
                                            ?>
                                        </div>
                                    </div>
                            <?php
                                } // WHILE LOOP CLOSEED
                            } else {
                                echo "<div class='error mt-5'>Sorry! There have no such kind of poset exist.</div>";
                                // echo "<div class='error mt-5'>No Posets Found For $MOrder Elements</div>";
                            } // IF CONDITION END
                            ?>
                            </div>
                        </div>
                    </div>
                <?php
            }

            // Execution of The SQL Code
            // $rowPD = mysqli_fetch_assoc($result);

                ?>
                <!-- </div>
                </div>
                </div> -->

                </section>

                <?php
                // }  // if (isset($_GET["Order"]) && !isset($_GET["Height"])) END
                ?>
    </main>

    <footer class="big-footer">
        <!-- Calling Functions  -->
        <?php
        ####################### MATRIX CONSTRUCTOR #################
        function MatrixConstruction($MOrder, $Height, $Width, $Matrix, $Posets)
        // function MatrixConstruction($MOrder, $Matrix, $Posets)
        {
            $k = 0;
            $Matrix = str_replace(' ', '', $Matrix);  // To remove the space between two elements in the matrix saved in the database.
            /* Full Row */
            // echo "<div class='border-start border-end border-5 border-dark m-5 col'>";
        ?>

            <div class='border border-1 row'>
                <!-- Two Equal Column [Matrix & Hasse Diagram] -->
                <div class='border-end col-6'>
                    <!-- Poset Matrix -->
                    <div class='row m-4'>
                        <!-- Matrix Notation [ -->
                        <div id="matrix-<?= $Matrix ?>" class='col border-start-end mx-auto h-100 position-relative' style='min-width: <?= $MOrder * 40 ?>px; max-width: <?= $MOrder * 40 + 30 ?>px;'>
                            <!-- Matrix Rows -->
                            <?php for ($i = 0; $i < $MOrder; $i++) { ?>
                                <div class='row m-auto' style='width: <?= $MOrder * 40 ?>px'>
                                    <!-- Matrix Row -->
                                    <?php
                                    for ($j = 0; $j < $MOrder; $j++) {
                                    ?>
                                        <div class='col'>
                                            <?php
                                            if ($i == $j) {
                                                echo "1";
                                            } else if ($i < $j) {
                                                echo "$Matrix[$k]";
                                                $k++;
                                            } else {
                                                echo "0";
                                            }
                                            ?>
                                        </div> <!-- col END -->
                                    <?php
                                    }
                                    ?>
                                </div> <!-- one matrix row end || row END -->
                            <?php
                            } // FOR LOOP CLOSED FOR POSET-MATRIX
                            ?>
                        </div> <!-- Matrix Notation ] || col END-->
                    </div> <!-- row END -->
                </div> <!-- col-6 END -->

                <!-- ============================================= 
                    Hasse diagram corresponding to the above poset 
                    ============================================= -->
                <div class='p-2 w-50 col-6 m-auto'>
                    <?php if (isset($_GET["matrix"]) && isset($_GET["SEs"])) {
                    ?>
                        <!-- Draw The Poset Which is just Inputed to search -->
                        <div class="text-center" data-bs-content="Draw The Hasse Diagram of The Poset Matrix.">
                            <canvas id="poset" width="200" height="200" class="border border-4 border-dark shadow-lg p-0">
                                Sorry! Canvas Is Not Supported In Your Browser. Please Search manually.
                            </canvas>
                        </div>
                    <?php
                    } else {
                    ?>
                        <a href='#' data-bs-toggle="modal" data-bs-target="#PosetMatrix-<?php echo $Matrix; ?>"><img title='Poset-Matrix' src='<?php echo $dir = "./Database/hasseDiagrams/allposets5_4_3.gif"; ?>' alt='Poset-Matrix Figure Goes Here.' width='150'></a>
                    <?php } ?>
                </div> <!-- Hasse Diagram END -->
                <!-- <td class='p-2 w-50'><a href='#' data-bs-toggle="modal" data-bs-target="#PosetMatrix-<?php echo $Matrix; ?>"><img title='Poset-Matrix' src='<?php // echo $dir = "./Database/hasseDiagrams/allposets$MOrder+'_'+$Height+'_'+$Width.gif"; 
                                                                                                                                                                    ?>' alt='Poset-Matrix Figure Goes Here.' width='150'></a></td> -->

                <!-- ################ MODAL FOR POSET-MATRIX FIGURE ############## -->
                <div class="modal fade" id="PosetMatrix-<?php echo $Matrix; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="PosetMatrixLabel-<?php echo $Matrix; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="PosetMatrixLabel-<?php echo $Matrix; ?>">
                                    <!-- Height & Width Of the Poset-Matrix Shown Here in Later -->
                                    <?php echo "$Posets Poset-Matrix For $MOrder Elements With&nbsp;Height&nbsp;" . (($Height > 0) ? $Height : 'Not Identified') . "&nbsp;&&nbsp;Width&nbsp;" . (($Width > 0) ? $Width : 'Not Identified'); ?>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div class="row">
                                        <!-- ####### Show The Hasse Diagram Of The Matrix ########## -->
                                        <div class="col"><img title='Poset-Matrix' src='<?php echo $dir; ?>' alt='Poset-Matrix Figure Goes Here.' title="Popover title" data-bs-content="Popover body content is set in this attribute."></a></div>
                                    </div>
                                </div>
                            </div> <!-- Modal Body END -->
                        </div> <!-- Modal Content END -->
                    </div> <!-- Modal Dialog END -->
                </div> <!-- Modal END -->
            </div> <!-- row End || [Matrix & Hasse Diagram] Shown-->
            <script>
                var matrixBeforeAfter = document.querySelector("#matrix-<?= $Matrix ?>");
                // console.log(matrixBeforeAfter);
                /* Set The Size Of The Paranthisis (Matrix Notation) According To Matrix Size */
                if (matrixBeforeAfter) {
                    matrixBeforeAfter.style.setProperty("--morder-font-size", "<?php echo $MOrder * 35 ?>px");
                }
                // selectedElemets = json.parse("<?php // echo $_GET["SEs"] 
                                                    ?>");
            </script>
        <?php
        } // MatrixConstruction() END
        ?>
        <script>
            // reDraw();

            const x = 50, // The gride start from x
                radius = 5,
                width = (document.getElementById("poset").width = (morder + 1) * x),
                height = (document.getElementById("poset").height = (morder + 1) * x),
                poset = document.getElementById("poset").getContext("2d");
        </script>
    </footer>

    <!-- Footer -->
    <?php
    mysqli_close($conn);
    include "footer.php";
    include "script.php";
    ?>