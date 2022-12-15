<?php 
session_start();

session_unset();
session_destroy();

header("Location: index.php");




// <?php
// $Height = 1;   // Current Height For $POrder // Number of Rows
// while ($Height <= $POrder) {  /* Every Posets Have Height From 1 To Order Of The Posets */
// ?>
<!--       <tr class="min-vw-100"> -->
<!--            <td class="border fs-5 text-center"> <?php // echo $Height; ?> </td> -->
//           <?php
//           $table = 0;        // k-th TableName 
//           while ($table < sizeof($tableName)) {
//           ?>
<!-- //                <td class="border fs-5 text-center"> -->
//                     <?php
//                     $query = "SELECT
//           $tableName[$table].Height
//       FROM
//           $tableName[$table]
//       WHERE
//           $tableName[$table].MatrixOrder = $POrder && $tableName[$table].Height = $Height;";
//                     $result_1 = mysqli_query($conn, $query) or die(mysqli_errno($conn) . ": " . mysqli_error($conn));
//                     $cp[$table] = mysqli_num_rows($result_1);
//                     ?>
//                     <!-- Linking To Show The Poset Matrix in Matrix Form -->
<!-- //                     <a data-bs-toggle='tooltip' title="Click To Show The Poset Matrix." class="text-dark text-decoration-none" href='<?php // if ($cp[$table] != 0) { -->
//                                                                                                                                             echo "ToMatrix.php?Table=$tableName[$table]&Order=$POrder&Height=";
//                                                                                                                                             echo $Height;
//                                                                                                                                        } ?>' class='text-decoration-none'>
//                          <?php
//                          // echo $cp[$table]
//                          ?>
//                          <span class='<?php // if ($cp[$table] == 0) {
//                                              echo "disabled'  data-bs-toggle='tooltip' title='No Posets Found For Height = " . $Height . "'";
//                                         } ?>'><?php // echo $cp[$table] ?></span>
//                     </a>
//                <?php
//                $table++;
//                echo "</td>";
//           }
//                ?>
//                <td class="border fs-5 text-center"> <?php // echo $cp[0] + $cp[1]; ?> </td>
//      </tr>

// <?php
//      $Height++;
// } // END WHILE ($Height <= $PORDER)
// ?>