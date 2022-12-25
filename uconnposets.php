<?php
include "header.php";
?>
<title>Account Of Posets</title>
</head>

<body class="bg-white-50 text-dark">
    <!-- Styles Every HTML markups if possible. -->
    <header class="header-section">
        <!-- This is Header Part of the page  -->
        <?php include "menu.php"; ?>
    </header>
    <a action="action" class="bg-warning bg-opacity-100 rounded-5" href='#' onclick="history.back()" value="Back" id="backPage"><img src="./styles/assets/images/backPage.png" alt="Back To Previous Page"></a>
    <main class="content-wrapper mt-5 pt-5 min-vh-100">
        <!-- Sub section of main contents -->
        <!-- ================= TOTAL POSETS-COUNT TABLE ============= -->
        <section id="search-section" class="px-3">
            <!-- Searched By POrder and Height in All Tables -->
            <!-- ############################### SEARCHED RESULT / OUTPUT Counter Table ######################################### -->
            <?php

            // Tables Name From Database 
            $tableName = ["connposets", "disconnposets"];
            // if (isset($_GET['totalPosets']) and !empty($_GET['MOrder'])) {
            $POrder = 16; //    $_GET["MOrder"];

            ?>
            <table id="searchedTable" class="mx-auto table">
                <!-- <thead></thead> -->
                <?php
                $tableName = $_GET['tableName'];
                $nelements = $_GET['nthelements'];
                tableFormate($tableName, $nelements);
                ?>
            </table>
            <!-- ######################################################################## -->
            <?php
            // } // END IF (ISSET($_GET['TOTALPOSETS']) AND !EMPTY($_GET['MORDER']))

            ?>
        </section>

        <!-- ############################### OUTPUT Table Details ######################################### -->
    </main>
    <footer class="big-footer"><wbr>
        <!-- This is footer part of the page  -->

        <?php
        /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Funtion SearchPosets @@@@@@@@@@@@ */
        /* Search All Posets With Height For Certain Order -> index.php */
        function tableFormate($tableName, $nelements)
        { ?>
            <table class="table table-striped-columns table-striped">
                <thead>
                    <tr class="text-center fs-3">
                        <th colspan="<?php echo $nelements + 1; ?>" class="text-capitalize">No. of Unlabeled <?php echo $tableName ?> Posets With <?php echo $nelements ?> Elements</th>
                    </tr>
                </thead>
                <?php
                // $nelements = $_GET['nelements'];
                if ($tableName == 'connposets') {
                    /* Connected Posets */
                    $n = 0; // Indexing (Header) of Height
                    for ($i = 0; $i <= $nelements; $i++) {  // Height loop $i
                        echo "<tr>"; ?>
                        <script>
                            /* Initialise Before Count For Height */
                            totalH = 0;
                        </script>
                        <?php
                        for ($j = 0; $j <= $nelements; $j++) {  // Width loop $j
                            if ($i == 0) {

                                /* Header for Width */
                                if ($j == 0) {
                                    echo "<th class='text-center; fs-5'><span style = 'vertical-align: bottom; text-nowrap;'>Height<wbr>&Downarrow; </span><span style = 'font-size: xxx-large; padding-left: 15px; padding-right: 15px;'>\</span> <span style = 'vertical-align: top'>Width<wbr>&Rightarrow;</span></th>";
                                } else if ($j == $nelements) {
                                    echo "<th class='text-center; fs-5'>Total</th>";
                                } else {
                                    echo "<th class='text-center; fs-5'>$j</th>"; ?>
                                    <script>
                                        /* Initialise Before Count For Width */
                                        var totalW_<?= $j ?> = 0;
                                    </script>
                                <?php
                                }
                                /* Header for Width END */
                            } else if ($j == 0 && $i != $nelements) {

                                /*Header For Height */
                                echo "<th class='text-center; fs-5'>";
                                echo $n = $i + 1;
                                echo "</th>";
                            } else if ($j == 0) {
                                echo "<th class='text-center; fs-5'>Total</th>";
                                /*Header For Height END */
                            } else if ($i < $nelements && $j < $nelements && $j > 0) {

                                /* Fill The Inner Cells*/
                                include "db_conn.php";
                                // $numElements = "SELECT $tableName.`Matrix` FROM `$tableName` WHERE `MatrixOrder` =  $nelements AND `Height` = $n AND `Width` = $j";
                                $numElements = "SELECT $tableName.`Matrix` FROM `$tableName` WHERE `MatrixOrder` =  $nelements && `Height` = $n";
                                $resultE = mysqli_query($conn, $numElements) or die("Some Error Found.");
                                $numE = $resultE->num_rows;
                                // echo "<pre>";
                                // print_r($resultE);
                                // echo $numE . "<br>";
                                // echo "</pre>";

                                echo "<td><a id='$n-$j' href='ToMatrix.php?Order=$nelements&Height=$n&Width=$j&Table=$tableName' class='text-decoration-none'>$numE</a></td>";
                                ?>
                                <script>
                                    /* Save the value for Height */
                                    var save1 = document.getElementById('<?php echo "$n-$j" ?>').innerHTML;
                                    totalH += parseInt(save1, 10);

                                    /* Save the value for Width */
                                    var save2_<?= $j ?> = document.getElementById('<?php echo "$n-$j" ?>').innerHTML;
                                    totalW_<?= $j ?> += parseInt(save2_<?= $j ?>, 10);

                                    // console.log(totalW_<?= $j ?>);
                                </script>
                            <?php
                                mysqli_close($conn);
                            } else if ($i == $nelements && $j < $nelements) { ?>
                                <td class='text-center;' id='<?= "TW-$n-$j" ?>'><?= "TW" ?></td>
                                <script>
                                    document.getElementById('<?= "TW-$n-$j" ?>').innerHTML = totalW_<?= $j ?>;
                                    // console.log(totalW);
                                    // totalW_<?= $j ?> = 0;
                                </script>
                            <?php
                            } else if ($j == $nelements && $i < $nelements) {
                                echo "<td class='text-center;' id='TH-$n-$j'>" . 'TH' . "</td>"; ?>
                                <script>
                                    // console.log(totalH);
                                    document.getElementById('<?= "TH-$n-$j" ?>').innerHTML = totalH;
                                </script>
                        <?php
                            }
                        }   /* Column ($j) END */
                        echo "</tr>";
                    } /* Row ($i) End */
                } else {

                    /* Disconnected Posets */
                    $n =  0; // Indexing (Header) of Height
                    for ($i = 0; $i <= $nelements; $i++) { // Height loop $i
                        echo "<tr>"; ?>
                        <script>
                            /* Initialise Before Count For Height */
                            totalH = 0;
                        </script>
                        <?php
                        for ($j = 0; $j <= $nelements; $j++) {
                            if ($i == 0) {
                                /* Header for Width */
                                if ($j == 0) {
                                    echo "<th class='text-center;fs-5;'><span style = 'vertical-align: bottom; text-nowrap;'>No. of Direct Terms<wbr>&Downarrow; </span><span style = 'font-size: xxx-large; padding-left: 15px; padding-right: 15px;'>\</span> <span style = 'vertical-align: top'>No. of<wbr>&Rightarrow;</span></th>";
                                } else if ($j == $nelements) {
                                    echo "<th class='text-center; fs-5'>Total</th>";
                                } else {
                                    echo "<th class='text-center; fs-5'>$j</th>"; ?>
                                    <script>
                                        /* Initialise Before Count For Width */
                                        var totalW_<?= $j ?> = 0;
                                    </script>
                                <?php
                                }
                                /* Header for Width END */
                            } else if ($j == 0 && $i != $nelements) {
                                /*Header For Height */
                                echo "<th class='text-center; fs-5'>";
                                echo $n = $i + 1;
                                echo "</th>";
                            } else if ($j == 0) {
                                echo "<th class='text-center; fs-5'>Total</th>";
                                /*Header For Height END */
                            } else if ($i < $nelements && $j < $nelements && $j > 0) {

                                /* Fill The Inner Cells*/
                                include "db_conn.php";
                                // $numElements = "SELECT $tableName.`Matrix` FROM `$tableName` WHERE `MatrixOrder` =  $nelements AND `Height` = $n AND `Width` = $j";
                                $numElements = "SELECT $tableName.`Matrix` FROM `$tableName` WHERE `MatrixOrder` =  $nelements && `Height` = $n";
                                $resultE = mysqli_query($conn, $numElements);
                                $numE = $resultE->num_rows;
                                // echo "<pre>";
                                // print_r($resultE);
                                // echo $numE . "<br>";
                                // echo "</pre>";

                                echo "<td><a id='D-$n-$j' href='ToMatrix.php?Order=$nelements&Height=$n&Width=$j&Table=$tableName' class='text-decoration-none'>$numE</a></td>";
                                ?>
                                <script>
                                    /* Save the value for Height */
                                    var save1 = document.getElementById('<?php echo "D-$n-$j" ?>').innerHTML;
                                    totalH += parseInt(save1, 10);

                                    /* Save the value for Width */
                                    var save2_<?= $j ?> = document.getElementById('<?php echo "D-$n-$j" ?>').innerHTML;
                                    totalW_<?= $j ?> += parseInt(save2_<?= $j ?>, 10);

                                    // console.log(totalW_<?= $j ?>);
                                </script>
                            <?php
                                mysqli_close($conn);
                            } else if ($i == $nelements && $j < $nelements) { ?>
                                <td class='text-center;' id='<?= "TWD-$n-$j" ?>'><?= "TW" ?></td>
                                <script>
                                    document.getElementById('<?= "TWD-$n-$j" ?>').innerHTML = totalW_<?= $j ?>;
                                    // console.log(totalW);
                                    // totalW_<?= $j ?> = 0;
                                </script>
                            <?php
                            } else if ($j == $nelements && $i < $nelements) {
                                echo "<td class='text-center;' id='THD-$n-$j'>" . 'TH' . "</td>"; ?>
                                <script>
                                    // console.log(totalH);
                                    document.getElementById('<?= "THD-$n-$j" ?>').innerHTML = totalH;
                                </script>
                <?php
                            }
                        }   /* Column ($j) END */
                        echo "</tr>";
                    } /* Row ($i) End */
                }
                ?>
            </table>
        <?php
        }

        ?>
    </footer>
    <?php include "footer.php"; ?>