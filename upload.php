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
    <header class="header-section mt-0 mb-5">
        <!-- This is Header Part of the page  -->
        <?php include "menu.php"; ?>
    </header>
    <main class="content-wrapper py-5">
        <!-- This is content-part of the page  -->
        <section id="connposets-upload-section" class="px-3">
            <!-- ################## MUST BE METHOD = POST FOR HANDLING FILES ##########################-->
            <div class="row">
                <div class="col-12 col-md-9">
                    <form class="form-control w-100 clearfix" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" id="inputForm">
                        <table class="updateTable w-100">
                            <thead>
                                <tr>
                                    <th class="text-center p-2 fs-4">Add New Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <!-- For catch multiple files by PHP using POST must be input name with array sign "[]" ?  -->
                                    <td><label for="mfiles">Enter The Poset-Matrices As DAT File</label><input data-bs-toggle='tooltip' title="Inser The Files Here" type="file" name="mfiles[]" id="Mfiles" placeholder="Upload The Data Files With Specific Name." multiple></td>
                                </tr>
                            </tbody>
                        </table>
                        <input class="text-info bg-transparent" type="submit" value="Upload" name="upload">
                        <!-- <input class="col-2 float-end bg-transparent text-danger" type="submit" value="Update" name="update"> -->
                    </form>
                </div>
                <div class="col-12 col-md-3">
                    <form class="form-control w-100 clearfix" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" id="inputForm">
                        <table class="updateTable w-100">
                            <thead>
                                <tr>
                                    <th class="text-center p-2 fs-4">Update To Allposets</th>
                                </tr>
                            </thead>
                        </table>
                        <!-- <input class="col-2 float-start text-info bg-transparent" type="submit" value="Upload" name="upload"> -->
                        <input class="bg-transparent text-danger" type="submit" value="Update" name="update">
                    </form>
                </div>
            </div>
            <!-- ######################################################################## -->

            <!-- ################## Show OUTPUT ################## -->
            <?php
            include "db_conn.php";
            /* Update Height & Width To Allposets From connposets & disconnposets, if difference found */
            if (isset($_POST["update"])) {
                echo "Updating To Allposets Table.";

                /* connposets to allposets */
                $SelectionQuery = "SELECT allposets.`idx`, allposets.`Height`, connposets.`Height`, connposets.`Width` From allposets, connposets where allposets.`Matrix` = connposets.`Matrix`";
                // $SelectionQuery = "INSERT INTO allposets (`Height`, `Width`) SELECT `Height`, `Width` From connposets where allposets.`Matrix` = connposets.`Matrix` and allposets.`Height` <> connposets.`Height`";
                $SelectionRun = mysqli_query($conn, $SelectionQuery);
                // $SelectionRun = mysqli_query($conn, $SelectionQuery) or die("Error Found-" . mysqli_errno($conn) . " Error is: " . mysqli_error($conn));

                // echo "<pre>";
                // print_r($SelectionRun);
                // echo "</pre>";

                /* Check Connection Error */
                if (!$SelectionRun) {
                    echo "Error Found";
                    exit();
                }
                // $nums = mysqli_num_rows($SelectionRun);

                /* Update Height & Width Of allposets From connected posets table */
                if (mysqli_num_rows($SelectionRun) > 0) {
                    while ($SelectedRows = mysqli_fetch_row($SelectionRun)) {
                        $idx = $SelectedRows[0];
                        $Height = $SelectedRows[2];
                        $Width = $SelectedRows[3];
                        if ($SelectedRows[1] != $Height) {
                            echo "$SelectedRows[1] & $Height Are Not Same.";
                            $updateQuery = "UPDATE `allposets` SET `Height` = '$Height', `Width` = '$Width' WHERE `allposets`.`idx` = '$idx'";
                            $updateRun = mysqli_query($conn, $updateQuery) or die("Error Found-" . mysqli_errno($conn) . " Error:" . mysqli_error($conn));
                        }
                        echo "<pre>";
                        print_r($SelectedRows);
                        echo "</pre>";
                    }
                    echo "Update Succesfull 'connposets'.";
                }

                /* disconnposets To allposets */
                $SelectionQuery1 = "SELECT allposets.`idx`, allposets.`Height`, disconnposets.`Height`, disconnposets.`Width` From allposets, disconnposets where allposets.`Matrix` = disconnposets.`Matrix`";
                // $SelectionQuery1 = "INSERT INTO allposets (`Height`, `Width`) SELECT `Height`, `Width` From disconnposets where allposets.`Matrix` = disconnposets.`Matrix` and allposets.`Height` <> disconnposets.`Height`";
                $SelectionRun1 = mysqli_query($conn, $SelectionQuery1);
                // $SelectionRun1 = mysqli_query($conn, $SelectionQuery1) or die("Error Found-" . mysqli_errno($conn) . " Error is: " . mysqli_error($conn));
                // echo "<pre>";
                // print_r($SelectionRun1);
                // echo "</pre>";
                if (!$SelectionRun1) {
                    echo "Error Found";
                    exit();
                }
                // $nums = mysqli_num_rows($SelectionRun1);

                /* update disconnposets To allposets */
                if (mysqli_num_rows($SelectionRun1) > 0) {
                    while ($SelectedRows1 = mysqli_fetch_row($SelectionRun1)) {
                        $idx1 = $SelectedRows1[0];
                        $Height = $SelectedRows1[2];
                        $Width = $SelectedRows1[3];

                        /* Update Only Different Heights & Widths */
                        if ($SelectedRows1[1] != $Height) {
                            echo "$SelectedRows1[1] & $Height Are Not Same.";
                            $updateQuery = "UPDATE `allposets` SET `Height` = '$Height', `Width` = '$Width' WHERE `allposets`.`idx` = '$idx1'";
                            $updateRun = mysqli_query($conn, $updateQuery) or die("Error Found-" . mysqli_errno($conn) . " Error:" . mysqli_error($conn));
                        }
                        // echo "<pre>";
                        // print_r($SelectedRows1);
                        // echo "</pre>";
                    }
                    echo "Update Succesfull For 'disconnposets'.";
                }
            }

            /* Upload The Files */
            if (isset($_POST['upload'])) {
                // echo "<pre>";
                // print_r($_FILES['mfiles']);
                // echo "</pre>";

                /* Image FILE UPLOAD */
                if (!empty($_FILES['mfiles']) && !empty($_FILES['mfiles']['name'])) {
                    /* If File Image Type */
                    $Folder = "Database/hasseDiagrams/";
                    $allowed_types = array('image/jpeg', 'image/png');
                    foreach ($_FILES['mfiles']['name'] as $key => $imageName) {

                        /* Check The Type Of The File */
                        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                        // echo $fileInfo;
                        $detected_type = finfo_file($fileInfo, $Folder . $imageName);

                        /* Validate The Extension */
                        if (in_array($detected_type, $allowed_types)) {
                            // echo "<br>An image \"" . $imageName . "\" is detected of required type: " . $detected_type;

                            /* Move the file to desired Folder */
                            $tmpName = $_FILES['mfiles']['tmp_name'][$key]; // Uploaded files saved here by default

                            // echo $Folder;
                            move_uploaded_file($tmpName, $Folder . $imageName);

                            echo "Uploaded Successfully.";
                        }

                        finfo_close($fileInfo);
                    }
                }

                // exit;
                // if(){}

                /* If The Files Are in Dat Type */
                $Folder = "Database/MatrixFiles/";
                foreach ($_FILES['mfiles']['name'] as $key => $filename) {      // FOR EACH FILES GET FROM INPUT FILED RUN THIS LOOP
                    // echo "<br>" . $key . "<br>";
                    // print_r($_FILES['mfiles']['name'][$key]);

                    if (!empty($_FILES['mfiles']) && !empty($filename)) {
                        /* MOVE THE FILE TO A FOLDER [FIXED DIRECTORY] TO READ THE FILE FROM THAT FOLDER */
                        $tmpName = $_FILES['mfiles']['tmp_name'][$key]; // Uploaded files saved here by default
                        // echo $Folder;
                        move_uploaded_file($tmpName, $Folder . $filename);

                        /* READ THE FILE AND STORE THE DATA IN $FILES */
                        $Files = file($Folder . $filename) or die("<span class='error'>No " . $filename . " named file exists in such directory.</span>");
                        // echo "File Updated:<pre>";
                        // print_r($Files);
                        // echo "</pre>";

                        /* EXTRACT DATABASE TABLE-NAME & ORDER OF THE MATRIX FROM THE FILE-NAME-STRING  */
                        $tableName = preg_split("/[0-9 . _]+/", "$filename");   // Remove numbers from $filename
                        $Numbers = preg_split("/[ a-z A-Z . _]+/", "$filename");     // filename Formate => allposets2.dat, connposets2_3.dat, disconnposets2_3.dat
                        // echo "<pre>";
                        // print_r($tableName);
                        // print_r($Numbers);
                        // echo "</pre>";

                        // Order of the Poset [tableNameMOrder_Height_Width.dat]
                        $MOrder = (int)$Numbers[1];
                        // @@@@@@@@@@@@@@@@@@@@@@@  IF THE FILE NAME CONTAIN CON OR DIS THEN IT HAS HEIGHT IN IT'S NAME STRING @@@@@@@@@@@@@@@@@@@@@@@@@ \\
                        if (substr($tableName[0], 0, 3) == 'con' or substr($tableName[0], 0, 3) == 'dis') {

                            //################## 
                            //##################  FOR CONNPOSETS.DAT & DISCONN-POSETS.DAT
                            //################## 

                            // $Height = (int)$Numbers[2];
                            // $Width = (int)$Numbers[3];
                            $Height = (!empty($Numbers[2]) && $Numbers[2] > 0) ? (int)$Numbers[2] : null;
                            $Width = (!empty($Numbers[3]) && $Numbers[3] > 0) ? (int)$Numbers[3] : null;
                            // echo $Width;
                            // if ($Numbers[2] == 0 && $Height == 0) {
                            //     continue;
                            // }

                            // CHECK THE TABLE IS ALREADY EXIST
                            $query = "SELECT $tableName[0].`idx` FROM $tableName[0]";
                            $result = mysqli_query($conn, $query);  // Can Select or Not

                            $Status = "the";        // THE TABLE IS NEW OR OLD 

                            /* If Such Table not exit in database */
                            if (!$result) {
                                //------------// ERROR NOTE: If Matrix not uploaded as it's order then increase the size VARCHAR(?) //--------------//
                                // CREATE THE TABLE IN THE SELECTED DATABASE IF NOT EXISTS i.e. QUERY FAILED: SELECT

                                // QUERY FAILED: SELECT
                                $createTable = mysqli_query($conn, "CREATE TABLE `$db_name`.`$tableName[0]`(
                                        `idx` INT(50) UNSIGNED NOT NULL AUTO_INCREMENT,
                                        PRIMARY KEY(`idx`),
                                        `MatrixOrder` INT(11) UNSIGNED NOT NULL,
                                        INDEX(`MatrixOrder`),
                                        `Matrix` VARCHAR(500) NOT NULL,
                                        UNIQUE(`Matrix`), 
                                        `Height` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                                        INDEX(`Height`),
                                        `Width` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                                        INDEX(`Width`),
                                        `Date` DATETIME NOT NULL
                                )") or die("<br/><span class='error'>" . mysqli_errno($conn) . ": " . mysqli_error($conn) . "</span><br/>");
                                $Status = "A New";
                                echo "<div class='error text-info text-center mx-auto'>A '$tableName[0]' Named Table Created.</div>";
                            } //=== END if (!$result)       //  SELECTION QUERY END

                            //========== UPLOAD THE EXTRACTED MATRICES TO THE DATABASE ==============//
                            $numUploadedM = 0;          // how many New Poset-matrix found.
                            if ($numUploadedM == 0) {
            ?>
                                <table id="Height-searchedTable" class="mx-auto border-top border-3 border-danger mt-5 table-border">
                                    <thead>
                                        <tr>
                                            <td colspan="4" class="text-center fw-bold fs-2 vw-100">Uploading Results <?php if (!empty($_GET['morder'])) {
                                                                                                                            echo " For Order = $MOrder";
                                                                                                                        }
                                                                                                                        ?></td>
                                        </tr>
                                        <tr>
                                            <th class="pb-1 fs-5 text-center text-light bg-gradient bg-dark border border-secondary">Matrix Order</th>
                                            <th class="pb-1 fs-5 text-center text-light bg-gradient bg-dark border border-secondary">Height</th>
                                            <th class="pb-1 fs-5 text-center text-light bg-gradient bg-dark border border-secondary">Width</th>
                                            <th class="pb-1 fs-5 text-center text-light bg-gradient bg-dark border border-secondary">Matrices</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    }
                                    // number of elements of the upper triangular poset-matrix / Number of elements in a line of the file.
                                    // $numElementofaM = ($MOrder ** 2 - $MOrder) / 2;
                                    echo "<br/>The Order of The Matrix is " . $MOrder;
                                    // echo "<br>The Height is ";
                                    // echo ($Height > 0) ? $Height : "continue" . "<br>";
                                    // echo "<br>The Width is ";
                                    // echo ($Width > 0) ? $Width : "Not Set" . "<br>";

                                    if ($Height > 0 || $Width > 0) {
                                        // Need to conver || to &&
                                        echo " With Height ", $Height, " & Width ", $Width;
                                    } else {
                                        /* If Height or Width Not set properly then don't upload this file */
                                        // continue;
                                        echo "<br>But Height & Width Not Set.";
                                    }
                                    // echo "<br> The Number of Lines in this file is " . 

                                    ///////////////// GET THE MATRIX FROM UPLAODED FILEs \\\\\\\\\\\\\\\
                                    $linesInFile = count($Files);
                                    $nthMatrix = preg_split("/[,]+/", "$Files[0]");
                                    // echo "<br> The Number of Elements in a line is " . 
                                    $numElementofaM = sizeof($nthMatrix);
                                    $lines = 0;
                                    while ($lines < $linesInFile) {     // RUN THE LOOP UPTO EOF'S LINES || fgets() not work with array.
                                        $ExtractMFromFile = trim($Files[$lines]);
                                        $k = 0;
                                        while ($k < 2 * $numElementofaM - 1) {
                                            $ExtractMFromFile[$k];
                                            if ($ExtractMFromFile[$k] === ',') {
                                                $ExtractMFromFile[$k] = ' ';
                                            } else {
                                                $ExtractMFromFile[$k] = (int) $ExtractMFromFile[$k];
                                                //  Save the elements of the upper triangular poset-matrix
                                            }
                                            $k++;
                                        }
                                        $Matrix = $ExtractMFromFile;
                                        // $Matrix = trim($ExtractMFromFile);

                                        //------------// ERROR NOTE: Not working in foreach() //--------------//
                                        $today = date("Y-m-j H:i:s");     // Without any text values => 2022-10-05 10:57:10 For DateTime var type

                                        ///////////////// UPLOAD THE MATRICES TO THE DATABASE TABLE \\\\\\\\\\\\\\\
                                        //------------// ERROR NOTE: Quot the TABLE & VALUES, if not, insertion failed without showing any error in browser //--------------//
                                        // exit;
                                        $queryFind  = "SELECT $tableName[0].`idx` FROM $tableName[0] WHERE $tableName[0].`Matrix` IN ('$Matrix')";
                                        $Find_1 = mysqli_query($conn, $queryFind);
                                        $rows = mysqli_fetch_assoc($Find_1);
                                        echo "<pre>";
                                        print_r($Find_1);
                                        echo "</pre>";

                                        $updated = false;
                                        // If New Matrix Found Then Insert
                                        if ($rows == 0) {
                                            echo "New Matrix Found";
                                            $queryInsertion = "INSERT INTO `$tableName[0]` (`idx`, `MatrixOrder`, `Matrix`, `Height`, `Width`, `Date`) VALUES (NULL, '$MOrder', '$Matrix', '$Height', '$Width', '$today')";
                                            $inset_1 = mysqli_query($conn, $queryInsertion);
                                        ?>
                                            <tr>
                                                <td class="border fs-5 text-center"> <?php echo $MOrder ?></td>
                                                <td class="border fs-5 text-center"> <?php echo $Height ?></td>
                                                <td class="border fs-5 text-center"> <?php echo $Width ?></td>
                                                <td class="border fs-5 text-center"> <?php echo $Matrix ?></td>
                                            </tr>
                                        <?php
                                            $numUploadedM = $numUploadedM + 1;      // How many new matrix inserted in the table 
                                        } else {        // What's The ERROR: No Old Matrix exist in table nor new matrix found :) 
                                            /* Update If Needed */
                                            echo "Old Matrix Found";
                                            // $updateQuery = "UPDATE `$tableName[0]` SET `Height` = $Height, `Width` = $Width, `idx` = $tableName.idx WHERE $tableName[0].`Matrix` = '$Matrix' AND $tableName[0].`Height` <> $Height";

                                            $updateQuery = "UPDATE `$tableName[0]` SET `Height` = '$Height', `Width` = '$Width' WHERE `$tableName[0]`.`idx` = $rows[idx] AND `$tableName[0]`.`Matrix` = '$Matrix' AND $tableName[0].`Height` <> $Height";
                                            $updateRun = mysqli_query($conn, $updateQuery);
                                            // echo "Updated Height ", $Height, " & Width ", $Width;
                                            $updated = true;
                                            // echo "<pre>";
                                            // print_r($updateRun);
                                            // echo "</pre>";
                                        ?>
                                            <tr>
                                                <td class="border fs-5 text-center"> <?php echo $MOrder ?></td>
                                                <td class="border fs-5 text-center"> <?php echo $Height ?></td>
                                                <td class="border fs-5 text-center"> <?php echo $Width ?></td>
                                                <td class="border fs-5 text-center"> <?php echo $Matrix ?></td>
                                            </tr>
                                            <tr>
                                                <td class="border fs-5 text-center" colspan="4"> <?php echo (!$updated) ? "<span class='error border border-dark text-center mx-auto'>" . mysqli_errno($conn) . ": The Matrix \" $Matrix \" already exists in \" $tableName[0] \".</span><br/>" : "Updated Height or Width as ", $Height, $Width; ?> </td>
                                            </tr>
                                    <?php
                                            // exit;
                                        }   //=== END IF (MYSQLI_QUERY($CONN, $QUERYINSERTION)) 
                                        $today = date("Y-m-j H:i:s");     // 2022-10-05 10:57:10
                                        $lines++;
                                    }   //===  END WHILE ($LINES < $LINESINFILE) 
                                    ?>
                                    <tr class="min-vw-100">
                                        <td class="border fs-5 text-center border border-danger border-3" colspan="4">
                                            <?php
                                            echo "<br/><span class='text-info'>Note:</span> <span class='text-danger'>$numUploadedM </span>New Matrices of Order $MOrder Uploaded From '$filename' named file to $Status table '$tableName[0]' in database '$db_name'.";
                                        } else {

                                            //################## 
                                            //##################  FOR ALLPOSETS.DAT
                                            ///////////////// GET THE MATRIX FROM UPLAODED FILEs \\\\\\\\\\\\\\\
                                            //################## 

                                            // CHECK THE TABLE ASSOCIATIVE NAMED TO THIS FILE IS EXISTS OR NOT.
                                            $queryA = "SELECT $tableName[0].`idx` FROM $tableName[0]";
                                            $resultA = mysqli_query($conn, $queryA);
                                            $Status = "the";        // THE TABLE / A NEW Table || IS TABLE NEW OR OLD 

                                            // CREATE THE TABLE IN THE SELECTED DATABASE IF NOT EXISTS i.e. QUERY FAILED: SELECT
                                            if (!$resultA) {
                                                $createTable = mysqli_query($conn, "CREATE TABLE `$db_name`.`$tableName[0]`(
                                                    `idx` INT(50) UNSIGNED NOT NULL AUTO_INCREMENT,
                                                    PRIMARY KEY(`idx`),

                                                    `MatrixOrder` INT(2) UNSIGNED NOT NULL,
                                                    INDEX(`MatrixOrder`),

                                                    `Matrix` VARCHAR(20) NOT NULL,
                                                    UNIQUE(`Matrix`), 

                                                    `IsomorphicToMatrix` VARCHAR(65535) NOT NULL,
                                                    UNIQUE(`IsomorphicToMatrix`), 

                                                    `Height` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                                                    INDEX(`Height`),

                                                    `Width` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                                                    INDEX(`Width`),
                                                    
                                                    `Date` DATETIME NOT NULL
                                                    )") or die("<br/><span class='error'>" . mysqli_errno($conn) . ": " . mysqli_error($conn) . "</span><br/>");
                                                $Status = "A New";
                                                echo "<div class='error text-info text-center mx-auto'>A '$tableName[0]' Named Table Created.</div>";
                                            } //=== END if (!$result)       //  Table Created

                                            //################# If Table Already Exists then UPLOAD THE EXTRACTED MATRICES TO THE DATABASE
                                            $numUploadedM = 0;          // how many Poset-matrix counted.
                                            if ($numUploadedM == 0) {
                                            ?>
                                                <table id="Height-searchedTable" class="mx-auto border-top border-3 border-danger mt-5 table-border">
                                                    <!-- Header of the Output table -->
                                                    <thead>
                                                        <tr>
                                                            <td colspan="4" class="text-center fw-bold fs-2 vw-100">Uploading Results <?php if (!empty($_GET['morder'])) {
                                                                                                                                            echo " For Order = $MOrder";
                                                                                                                                        }
                                                                                                                                        ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="pb-1 fs-5 text-center bg-gradient bg-dark border border-secondary">Matrices</th>
                                                        </tr>
                                                    </thead>
                                                    <!-- Body of the output table -->
                                                    <tbody>
                                                        <?php
                                                    }
                                                    // number of elements of the upper triangular poset-matrix / Number of elements in a line of the file.
                                                    // $numElementofaM = ($MOrder ** 2 - $MOrder) / 2;
                                                    echo "<br/>The Order of The Matrix is " . $MOrder;
                                                    echo "<br> The Number of Lines in this file is " . $linesInFile = count($Files);
                                                    $nthMatrix = preg_split("/[,]+/", "$Files[0]");
                                                    echo "<br> The Number of Elements in a line is " . $numElementofaM = sizeof($nthMatrix);

                                                    /* Insert Every Poset-Matrix As A New Row To The Database Table */
                                                    $lines = 0;
                                                    //------------// ERROR NOTE: Not working in foreach() //--------------//
                                                    while ($lines < $linesInFile) {     // RUN THE LOOP UPTO EOF'S LINES
                                                        $ExtractMFromFile = trim($Files[$lines]);
                                                        $k = 0;
                                                        while ($k < 2 * $numElementofaM - 1) {
                                                            $ExtractMFromFile[$k];
                                                            if ($ExtractMFromFile[$k] === ',') {
                                                                $ExtractMFromFile[$k] = ' ';
                                                            } else {
                                                                $ExtractMFromFile[$k] = (int) $ExtractMFromFile[$k];
                                                                //  Save the elements of the upper triangular poset-matrix /* Array To Matrix */
                                                            }
                                                            $k++;
                                                        }
                                                        $Matrix = $ExtractMFromFile;
                                                        $today = date("Y-m-j H:i:s");     // Without any text values like "2022-10-05 10:57:10" For DateTime var type

                                                        ///////////////// UPLOAD THE MATRICES TO THE DATABASE TABLE \\\\\\\\\\\\\\\
                                                        // Check The Matrix Already Exists or Not.
                                                        $queryFind  = "INSERT INTO `$tableName[0]` (`idx`, `Matrix`, `MatrixOrder`, `Date`) VALUES (NULL, '$Matrix', '$MOrder', '$today')";
                                                        $inset_2 = mysqli_query($conn, $queryFind);
                                                        if ($inset_2) {      // If New Matrix Found Then Insert
                                                        ?>
                                                            <tr>
                                                                <td class="border fs-5 text-center"> <?php echo $Matrix ?></td>
                                                            </tr>
                                                        <?php
                                                            $numUploadedM = $numUploadedM + 1;      // How many new matrix inserted in the table 
                                                        } else {        // What's The ERROR: No Old Matrix exist in table nor new matrix found :) 
                                                        ?>
                                                            <tr>
                                                                <td class="border fs-5 text-center" colspan="4"> <?php echo "<span class='error border border-dark text-center mx-auto'>" . mysqli_errno($conn) . ": The Matrix \" $Matrix \" already exists in \" $tableName[0] \".</span><br/>"; ?> </td>
                                                            </tr>
                                                    <?php
                                                        }   //=== END IF (MYSQLI_QUERY($CONN, $QUERYINSERTION)) 
                                                        $today = date("Y-m-j H:i:s");     // 2022-10-05 10:57:10
                                                        $lines++;
                                                    }   //===  END WHILE ($LINES < $LINESINFILE) 
                                                    ?>
                                                    <tr class="min-vw-100">
                                                        <td class="border fs-5 text-center border border-danger border-bottom-0" colspan="4">
                                                <?php
                                                echo "<br/><span class='text-info'>Note:</span> <span class='text-danger'>$numUploadedM </span>New Matrices of Order $MOrder Uploaded From '$filename' named file to $Status table '$tableName[0]' in database '$db_name'.";
                                            }
                                            // @@@@@@@@@@@@@@@@@@@@@@@  END IF THE FILE NAME CONTAIN "CON" OR "DIS" THEN IT HAS HEIGHT @@@@@@@@@@@@@@@@@@@@@@@@@ \\
                                        } else {
                                            echo "<div class='error border border-dark text-center mx-auto'> Error - " . mysqli_errno($conn) . ": Fields Must Not Be Empty.</div>";
                                        } //=== END if(isset($_FILES['mfiles']) && !empty($filename))
                                        echo "</td>
                                            </tr>";
                                    }  //=== END  foreach ($_FILES['mfile']['name'] as $key => $filename)
                                                ?>
                                                    </tbody>
                                                </table>
                                            <?php
                                        } // END IF (ISSET($_POST['UPLOAD_1'])) 
                                            ?>

        </section>
    </main>
    <script src="./styles/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
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