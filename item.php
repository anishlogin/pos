<?php 
    include "db.php";
    include "functions.php";
	error_reporting(0);
    tryLogin();
    $product = [];
    if(isset($_GET["id"])){

        $sql = "select * from products where id=".$_GET['id'];

        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
        }
        
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
                            <li class="breadcrumb-item active">ITEM ADD / EDIT</li>
                        </ol>
                        <div class="row">
                            <div class="col-md-12">
                                <form action="additem.php" method="post">
                                    <div class="row">
                                        
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="small mb-1" for="name">Name<span class="required_field">*</span></label>
                                            <input class="form-control py-4" id="name" name="name" value="<?php echo $product['name']?>" type="text" placeholder="Enter Name" required/>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="code">Code<span class="required_field">*</span></label>
                                                    <input class="form-control py-4 "  id="code"  name="code" value="<?php echo $product['code']?>" type="text" placeholder="Enter Code" required/>
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="small mb-1"></label>
                                                    <div onclick="randomBarcode()" id="random_barcode" style="cursor:pointer;margin-top:15px;" >
                                                        <i class="fas fa-sync"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="small mb-1" for="qty">QTY<span class="required_field">*</span></label>
                                            <input class="form-control py-4 " id="qty"  name="qty" value="<?php echo $product['qty']?>" type="text" placeholder="Enter QTY" required/>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="small mb-1" for="unit">UNIT<span class="required_field">*</span></label>
                                            <select  class="form-control unit" id="unit" name="unit" required>
                                                <option value="">Select Unit</option>
                                                <option value="Kgs." <?php echo $product['unit'] == 'Kgs.' ? 'selected' :'' ?> >Kgs</option>
                                                <option value="Pkt." <?php echo $product['unit'] == 'Pkt.' ? 'selected' :'' ?> >Pkt</option>
                                                <option value="Nos." <?php echo $product['unit'] == 'Nos.' ? 'selected' :'' ?> >Nos</option>
                                            </select>         
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="small mb-1" for="mrp">MRP<span class="required_field">*</span></label>
                                            <input class="form-control py-4 " id="MRP"  name="mrp" value="<?php echo $product['mrp']?>" type="text" placeholder="Enter MRP" required/>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="small mb-1" for="retail_price">Retail Price<span class="required_field">*</span></label>
                                            <input class="form-control py-4 " id="retail_price"  name="retail_price" value="<?php echo $product['retail_price']?>" type="text" placeholder="Enter Retail Price" required/>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="small mb-1" for="retail_price">Rate<span class="required_field">*</span></label>
                                            <input class="form-control py-4 " id="rate"  name="rate" value="<?php echo $product['rate']?>" type="text" placeholder="Enter Your Price" required/>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    </div>

                                    <div class="row" id="barcode" data-item = '<?php echo $product['code']?>' style="margin-bottom: 10px;">
                                        <?php if($product['code']){ ?>
                                            <img  alt='<?php echo $product['code']?>' style="margin:auto;display: inline-block;" src='barcode.php?text=<?php echo $product['code']?>'  width="110" height="30" />

                                        <?php } ?>
                                    </div>
                                    <div class="row">
                                        <input type="hidden" name="id" value="<?php echo $product['id']?>">
                                        <div class="form-group">
                                            <label class="small mb-1" for="print_count">Print Count<span class="required_field">*</span></label>
                                            <input class="form-control py-4 " id="print_count"  name="print_count" value="" type="text" placeholder="Enter Print Count"/>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                        <input type="button" id="print_barcode"  value="Print Barcode" class="btn btn-primary" style="margin: auto;">
                                        <input type="submit" name="submit" value="SUBMIT" class="btn btn-success" style="margin: auto;">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div style="width: 40%">
                        <div id="barcode_div">
                            
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
        <script type="text/javascript" src="assets/js/jQuery.print.min.js"></script>
        <script src="assets/js/dataTables/dataTables.min.js" crossorigin="anonymous"></script>
        <script src="assets/js/app.js"></script>
    </body>
</html>
<script>
  
</script>