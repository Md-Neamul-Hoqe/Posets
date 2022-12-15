<?php
include "db_conn.php";
session_start();
?>
<nav class="navbar navbar-expand-md navbar-light border bg-white shadow fixed-top">
    <div class="container-fluid">
        <h1><a class="navbar-brand fs-1" href="./">Poset Database</a></h1>
        <button class="navbar-toggler btn" type="button" title="Click To Toggle" data-bs-toggle="collapse" data-bs-target="#mynavbar" aria-controls="mynavbar" aria-label="Toggle navigation" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="mynavbar">
            <!-- <nav class="navbar-nav me-auto"> -->
            <ul id="menu-bar" class="navbar-nav mx-auto">
                <?php
                if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) { ?>
                    <li class="nav-item"><a class="fs-3 nav-link active" href="./index.php">Home</a></li>
                    <li class="nav-item"><a class="fs-3 nav-link" href="./upload.php">Add More</a></li>
                    <li class="nav-item"><a class="fs-3 nav-link" href="./table.php">Search Posets</a></li>
                    <li class="nav-item"><a class="fs-3 nav-link" href="./logout.php">Logout</a></li>
                <?php
                } else {
                ?>
            </ul> <!-- END #menu-bar -->
            <!-- Login Form for Admin  -->
            <form id="LogIn" action="login.php" method="post" class="d-flex ms-auto">
                <label class="d-none">User Name</label>
                <input class="me-2 <?php if (isset($_GET['error1'])) {
                                        echo "border-1 border-danger";
                                    }
                                    ?>" type="text" name="uname" placeholder="<?php if (isset($_GET['error1'])) {
                                                                                    echo $_GET['error1'];
                                                                                } else {
                                                                                    echo  "Admin Name";
                                                                                } ?>">
                <label class="d-none">User Name</label>
                <input class="me-2 <?php if (isset($_GET['error2'])) {
                                        echo "border border-danger";
                                    } ?>" type="password" name="password" placeholder="<?php if (isset($_GET['error2'])) {
                                                                                            echo "$_GET[error2]";
                                                                                        } else {
                                                                                            echo  "Password";
                                                                                        } ?>">
                <button class="btn btn-light border border-warning h-75 fw-bolder text-nowrap" type="submit">Log-in</button>
                <?php if (isset($_GET['error'])) {
                        echo "<br/><span class='error'> $_GET[error] </span>";
                    } ?>
            </form>
        <?php
                }
        ?>
        </div> <!-- END #mynavbar -->
    </div> <!-- END container-fluid -->
</nav>