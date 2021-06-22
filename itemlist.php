<?php 
    include "db.php";
    include "functions.php";
    tryLogin();
    $sql = "select * from products";

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $result = mysqli_fetch_all($result,MYSQLI_ASSOC);
    }
    else{
        $result = [];
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - SB Admin</title>
        <link href="assets/css/styles.css" rel="stylesheet" />
        <link href="assets/css/bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="assets/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="index.html">Lakshmi Super Market</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">Settings</a>
                        <a class="dropdown-item" href="#">Activity Log</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="./logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <?php include "leftsidenav.php" ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">ITEM LIST</li>
                        </ol>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="productTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>NAME</th>
                                            <th>CODE</th>
                                            <th>QTY</th>
                                            <th>UNIT</th>
                                            <th>MRP</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($result as $key => $item){ ?>
                                            <tr>
                                                <td><?php echo $item["id"] ?></td>
                                                <td><?php echo $item["name"] ?></td>
                                                <td><?php echo $item["code"] ?></td>
                                                <td><?php echo $item["qty"] ?></td>
                                                <td><?php echo $item["unit"] ?></td>
                                                <td><?php echo $item["mrp"] ?></td>
                                                <td><a href="item.php?id=<?php echo $item['id'] ?>" class="badge badge-warning">Edit</a>
                                                   <a href="deleteitem.php?id=<?php echo $item['id'] ?>" class="badge badge-danger">Delete</a>
                                                   
                                                
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            
                        </div>
                        <div class="card mb-4">
                            
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="assets/js/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
        <script src="assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="assets/js/scripts.js"></script>
        <script src="assets/js/dataTables/dataTables.min.js" crossorigin="anonymous"></script>
        <link href="assets/css/dataTables/dataTables.min.css" rel="stylesheet">
        <script type="text/javascript">
            $(document).ready(function(){
                $("#productTable").dataTable()
                $("input[type='search']").focus()
            })
        </script>
    </body>
</html>
