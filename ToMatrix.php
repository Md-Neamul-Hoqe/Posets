<?php include "header.php"; ?>
<title>Poset-Matrices || Order Matrix</title>
</head>

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
        $MOrder = $_GET["Order"];
        if (isset($_GET["matrix"])) {
            /* Session Start */
            // session_start();

            /* Get The Matrix From Drawing Tool */
            $Matrix = $_GET["matrix"];
            $SelectedElements = $_SESSION["SEs"];
            // echo "<pre>";
            // // print_r($SelectedElements);
            // print_r($_SESSION);
            // echo "</pre>";

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
                                                <button onclick="update()" class='border border-3 p-3 rounded'>Search Up To <?= $num ?><sup> <?= $place ?> </sup> Posets By Search Box.</button>

                                            <?php
                                                if ($num < $Max) {
                                                    // if ($num < $_POST['MAX']) {
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
                <script>
                    function update() {
                        console.log("Update() called.")
                        <?php
                        if (isset($_POST["Min"])) {
                            $_POST["Min"] = $MatrixNo;
                        }
                        if (isset($_POST["Max"])) {
                            $_POST["Max"] = ($MatrixNo + $Interval > $num) ? $num : $MatrixNo + $Interval;
                        }

                        // $Max = ($MatrixNo + $Interval > $num) ? $num : $MatrixNo + $Interval;

                        // 
                        ?>
                        // console.log(document.getElementById("Min").innerHTML = "<?php // echo $_POST["MIN"] = ($Start = $MatrixNo) 
                                                                                    ?>");
                        document.getElementById('Min').value = "<?php echo $MatrixNo ?>";
                        document.getElementById('Max').value = "<?php echo ($MatrixNo + $Interval > $num) ? $num : ($MatrixNo + $Interval)
                                                                ?>";
                        // document.write(document.forms[1]);
                        document.getElementById("IntervalForm").submit();
                        // console.log(form[0]);
                        // document.form[0].submit();
                        // location.reload();
                    }
                </script>
                </section>

                <?php
                // }  // if (isset($_GET["Order"]) && !isset($_GET["Height"])) END
                ?>
    </main>

    <footer class="big-footer">
        <!-- Footer -->
        <?php
        mysqli_close($conn);
        include "footer.php";
        ?>

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
                        <div id="matrix-<?= $Matrix ?>" class='col border-start-end mx-auto position-relative' style='min-width: <?= $MOrder * 40 ?>px; max-width: <?= $MOrder * 40 + 30 ?>px;'>
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
                    <a href='#' data-bs-toggle="modal" data-bs-target="#PosetMatrix-<?php echo $Matrix; ?>"><img title='Poset-Matrix' src='<?php echo $dir = "./Database/hasseDiagrams/allposets5_4_3.gif"; ?>' alt='Poset-Matrix Figure Goes Here.' width='150'></a>
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
                // var matrixBeforeAfter = document.getElementsByClassName("border-start-end");
                // var matrixBeforeAfter = document.querySelector("div.border-start-end");
                console.log(matrixBeforeAfter);

                // function changeColor() {
                // 2. Modify its custom css properties
                // matrixBeforeAfter.style.setProperty("--check-primary", "#607d8b");
                if (matrixBeforeAfter) {
                    matrixBeforeAfter.style.setProperty("--morder-font-size", "<?php echo $MOrder * 25 ?>px");
                }
                // document.querySelectorAll(".border-start-end").forEach(elem => elem.addEventListener("load",
                //     () => {
                //         document.querySelector(".border-start-end.mx-auto.position-relative").style.setProperty("--morder-font-size", "<?php echo $MOrder * 25 ?>px");
                //     }));
                // // }
            </script>
        <?php
        } // MatrixConstruction() END
        ?>
    </footer>