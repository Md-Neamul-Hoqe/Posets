<?php include "header.php"; ?>
<title>Poset Database</title>
</head>

<body class="bg-white text-dark">
     <!-- Styles Every HTML markups if possible. -->
     <header class="header-section">
          <!-- This is Header Part of the page  -->
          <?php include "menu.php"; ?>
     </header>
     <main class="content-wrapper pt-5 min-vh-100">
          <!-- Sub section of main contents -->
          <!-- ================= TOTAL POSETS-COUNT TABLE ============= -->
          <section id="drawing-section" class="px-3 mt-5">
               <!-- Set The Matrix Order / Number of the Poset Elements -->
               <div class="form">
                    <!-- Input the order of the matrix -->
                    <form id="PosetMatrix" style="max-width: 600px; margin-left: auto; margin-right: auto;" action="#" method="GET">
                         <label for="MOrder" class="form-label" hidden>Order Of The Matrix</label>
                         <select style="max-width: 100%;" class="form-select form-select-lg" name="morder" id="MOrder" data="<?php echo isset($_GET["morder"]) ? $_GET["morder"] : 5; ?>" autofocus>

                              <?php
                              /* Select all unique orders from Database "Posets" on Table 'allposets' */

                              // $FindTable = "SELECT allposets.`idx` FROM allposets";
                              // if (!mysqli_query($conn, $FindTable)) {
                              //      die("Sorry! No such data found. Coming Soon...");
                              // }

                              $sqlQuery = "SELECT DISTINCT allposets.`MatrixOrder` FROM allposets";
                              $result = mysqli_query($conn, $sqlQuery) or die("Sorry. Required Data Not Found In Database." . mysqli_errno($conn) . ": " . mysqli_error($conn));

                              // $num = $result->num_rows;
                              while ($Orders = mysqli_fetch_assoc($result)) {
                                   echo "<option value='$Orders[MatrixOrder]' class='fs-5 lh-lg'>Order $Orders[MatrixOrder]</option>";
                              }
                              ?>

                         </select>
                         <input style="width: 150px;" class="px-0 mt-2 btn btn-outline-dark border-opacity-25 border" type="submit" name="submitOrder" value="Save Order">

                         <!-- Show Drawing Tool Button If Matrix Order is set. -->
                         <?php echo isset($_GET['morder']) ? '<a style="width: 150px;" class="btn btn-outline-dark float-end mt-2 border-opacity-25 border" href="#" data-bs-toggle="modal" data-bs-target="#DrawPoset" onkeydown="DrawingByKeyboard()">Drawing Tools</a>' : null ?>
                    </form>

                    <!-- To Convert JS variable To PHP Variable -->
                    <div id="outputMatrix" hidden></div>
               </div>

               <!-- ################ MODAL FOR POSET-MATRIX DRAWING ############## -->
               <div class="modal fade" id="DrawPoset" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                         <div class="modal-content">
                              <div class="modal-header">
                                   <h3 class="modal-title" id="modalTitle">
                                        Drawing Tool
                                   </h3>
                                   <div class="ms-auto" id="IsKeyboard">Keyboard Feature is <span class="text-info">OFF</span>. <br> <span class="text-dark">Close the modal & press <kbd>Enter</kbd> to <span class="text-info">ON</span> this feature</span>.</div>
                                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                   <div class="container-fluid">
                                        <div class="row">
                                             <div class="col">
                                                  <!-- ####### Draw The Hasse Diagram of The Poset Matrix ########## -->
                                                  <div class="text-center" data-bs-content="Draw The Hasse Diagram of The Poset Matrix.">
                                                       <canvas id="poset" width="200" height="200" class="border border-4 border-dark shadow-lg p-0">
                                                            Sorry! Canvas Is Not Supported In Your Browser. Please Search manually.
                                                       </canvas>
                                                       <!-- Out Put [fill/unfill/selected/connected/disconnected] -->
                                                       <!-- <div class="position-fixed border-1 border-dark" id="weDone" hidden></div> -->

                                                       <!-- Tab Feature's Status -->
                                                       <div class="ms-auto text-info" id="IsTab" hidden>Enter <kbd>Esc</kbd> to back to 'Drawing Tool'.</div>

                                                       <!-- Clear The Canvas -->
                                                       <input type="submit" onclick="resetCanvas()" value="Clear" class="position-absolute fixed-right border-1 border-dark btn btn-light text-danger">

                                                       <!-- Submit The Poset -->
                                                       <input onclick="getMatrix()" name="submitPoset" type="submit" class="text-decoration-none text-light border btn btn-lg btn-dark" value="Submit">
                                                       <!-- <input onclick="getMatrix()" name="submitPoset" type="submit" class="text-decoration-none text-success border" data-bs-dismiss="modal" aria-label="Close"> -->
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="modal-footer">
                                   <div id="ShowErrors" class="error"></div>
                                   <!-- <div id="Coords" class="text-info bg-gradient"></div> -->
                              </div>
                         </div>
                    </div>
               </div>
          </section>
          <section id="search-section" class="px-3 mt-5">

               <!-- ############################### SEARCHED RESULT / OUTPUT Counter Table ######################################### -->
               <?php

               /* TABLES NAMES FROM DATABASE */
               $queryTable = "SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = 'Posets' AND TABLE_NAME <> 'users';";
               $sql = mysqli_query($conn, $queryTable);
               if (mysqli_num_rows($sql) > 0) {
                    $i = 0;
                    while ($rows = mysqli_fetch_assoc($sql)) {
                         // echo "<br>" . $tableName[$i++] = $rows["TABLE_NAME"];
                         $tableName[$i++] = $rows["TABLE_NAME"];

                         /* store first 3 table names [allposets, connposets, disconnposets] */
                         if ($i == 3) break;
                    }
               } /* Extracted Table Name From Posets Database. */


               ?>
               <!-- Short List of The Poset Database -->
               <table id="searchedTable" class="mx-auto table table-striped-columns table-striped fw-semibold">
                    <thead>
                         <tr>
                              <td colspan="4" class="text-center fw-bold fs-1 pb-0">No. of Unlabeled Posets</td>
                         </tr>
                         <tr>
                              <th class="fs-5 fw-bolder text-center border border-dark px-3">No. of Elements</th>
                              <th class="fs-5 fw-bolder text-center border border-dark px-3">No. of Connected</th>
                              <th class="fs-5 fw-bolder text-center border border-dark px-3">No. of Disconnected</th>
                              <th class="fs-5 fw-bolder text-center border border-dark px-3">No. of Posets</th>
                         </tr>
                    </thead>
                    <tbody>
                         <tr>
                              <td><?php echo $nelements = 01; ?></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[1]"; ?>'> 1</a></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[2]"; ?>'> 0</a></td>
                              <td>1</td>
                         </tr>
                         <tr>
                              <td><?php echo $nelements = 2; ?></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[1]"; ?>'> 1</a></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[2]"; ?>'> 1</a></td>
                              <td>2</td>
                         </tr>
                         <tr>
                              <td><?php echo $nelements = 3; ?></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[1]"; ?>'> 3</a></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[2]"; ?>'> 2</a></td>
                              <td>5</td>
                         </tr>
                         <tr>
                              <td><?php echo $nelements = 4; ?></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[1]"; ?>'> 10</a></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[2]"; ?>'> 6</a></td>
                              <td>16</td>
                         </tr>
                         <tr>
                              <td><?php echo $nelements = 5; ?></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[1]"; ?>'> 44</a></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[2]"; ?>'> 19</a></td>
                              <td>63</td>
                         </tr>
                         <tr>
                              <td><?php echo $nelements = 6; ?></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[1]"; ?>'> 238</a></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[2]"; ?>'> 80</a></td>
                              <td>318</td>
                         </tr>
                         <tr>
                              <td><?php echo $nelements = 7; ?></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[1]"; ?>'> 1,650</a></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[2]"; ?>'> 395</a></td>
                              <td>2,045</td>
                         </tr>
                         <tr>
                              <td><?php echo $nelements = 8; ?></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[1]"; ?>'> 14,512</a></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[2]"; ?>'> 2,487</a></td>
                              <td>16,999</td>
                         </tr>
                         <tr>
                              <td><?php echo $nelements = 9; ?></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[1]"; ?>'> 1,63,341</a></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[2]"; ?>'> 19,890</a></td>
                              <td>1,83,231</td>
                         </tr>
                         <tr>
                              <td><?php echo $nelements = 10; ?></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[1]"; ?>'> 23,60,719</a></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[2]"; ?>'> 20,655</a></td>
                              <td>25,67,284</td>
                         </tr>
                         <tr>
                              <td><?php echo $nelements = 11; ?></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[1]"; ?>'> 4,39,44,974</a></td>
                              <td><a href='<?php echo "uconnposets.php?nthelements=$nelements&tableName=$tableName[2]"; ?>'> 28,04,453</a></td>
                              <td>4,67,49,427</td>
                         </tr>
                         <tr>
                              <td><?php echo $nelements = 12; ?></td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                         </tr>
                         <tr>
                              <td><?php echo $nelements = 13; ?></td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                         </tr>
                         <tr>
                              <td><?php echo $nelements = 14; ?></td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                         </tr>
                         <tr>
                              <td><?php echo $nelements = 15; ?></td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                         </tr>
                         <tr>
                              <td><?php echo $nelements = 16; ?></td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                         </tr>
                    </tbody>
               </table>
               <!-- ######################################################################## -->
               <?php
               // } // END IF (ISSET($_GET['TOTALPOSETS']) AND !EMPTY($_GET['MORDER']))
               ?>
          </section>
          <!-- ############################### OUTPUT Table Details ######################################### -->
     </main>
     <footer class="big-footer">
          <script>
               morder = document.getElementById("MOrder").value = <?php echo isset($_GET["submitOrder"]) ? $_GET['morder'] : 5; ?>;
               /* Get transisional cover matrix Then Search Poset By This  */
               function getMatrix() {
                    // let L = selectedElements.length;
                    let morder = <?php echo isset($_GET["morder"]) ? $_GET["morder"] : 0; ?>;

                    if (morder === selectedElements.length) {
                         var matrix = transitionalCoverMatrix();

                         /* Convert JS variable to PHP variable */
                         /* Search with the matrix to search page (ToMatrix.php) */
                         document.location = './ToMatrix.php?Order=' + morder + '&matrix=' + matrix + '&SEs=' + JSON.stringify(selectedElements);
                         // document.location = './ToMatrix.php?Order=<?php // echo isset($_GET["morder"]) ? $_GET["morder"] : 5; 
                                                                      ?>&matrix=' + matrix;
                         // document.location = './ToMatrix.php?Order=<?php // echo isset($_GET["morder"]) ? $_GET["morder"] : 5; 
                                                                      ?>&matrix=' + matrix + '&SEs=' + selectedElements;
                         return;
                    } else {
                         // $().preventDefault();
                         // document.getElementById("ShowErrors").hidden = false;
                         document.getElementById("ShowErrors").innerHTML = "Please Select Exactly " + morder + " Elements.";
                         return false;
                    }
               }

               document.getElementById("ShowErrors").hidden = true; /* Hidden By Default */
               /* ========= Initialising The Variables =========== */
               const x = 50, // The gride start from x
                    radius = 5,
                    width = (document.getElementById("poset").width = (morder + 1) * x),
                    height = (document.getElementById("poset").height = (morder + 1) * x),
                    poset = document.getElementById("poset").getContext("2d");
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
               // grids();
               console.log(poset);
          </script>
     </footer>

     <?php
     /* Drawing Tool */
     include "script.php";
     ?>
     <?php
     /* Footer with all footer links */
     include "footer.php";
     ?>
     <script>
          reDraw();
     </script>