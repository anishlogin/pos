<?php 
    include "db.php";
    include "functions.php";
    tryLogin();
    
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
                            <li class="breadcrumb-item active">Bill LIST</li>
                        </ol>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="salesTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Bill Date</th>
                                            <th>Bill No</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Dealer Price Total</th>
                                            <th style="background-color: #ffc1bb;"><span class="dealer_price"></span></th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Bill Total</th>
                                            <th style="background-color: #c6c5ff;"><span class="billtotal"></span></th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Profit</th>
                                            <th style="background-color: #c2ffbb;"><span class="profit"></span></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
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
        <script type="text/javascript">
            $(document).ready(function(){
                function amountFormat(x) {
                    x = parseFloat(x).toFixed(2)
                    if (x) {

                        return "₹ "+x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                    return "₹ 0";
                }
                function dateFormat(date){
                    dateString = new Date(date)
                    var month = dateString.toLocaleString("en-IN", { month: "short" });
                    var day = dateString.toLocaleString("en-IN", { weekday: "short" });
                    var date = dateString.getDate();
                    var year = dateString.getFullYear();
                    var newDate =day+" "+date+" "+month+" "+year
                    return date+" "+month+" "+year
                }
                function timeFormat(date){
                    dateString = new Date(date)
                    var month = dateString.toLocaleString("en-IN", { month: "short" });
                    var day = dateString.toLocaleString("en-IN", { weekday: "short" });
                    var date = dateString.getDate();
                    var year = dateString.getFullYear();
                    var hours = dateString.getHours();
                    var minutes = dateString.getMinutes();
                    if (hours.toString().length == 1) {
                        hours = "0" + hours;
                    }
                    if (minutes.toString().length == 1) {
                        minutes = "0" + minutes;
                    }
                    return date+" "+month+" "+year+" "+hours+":"+minutes
                }
                var dealerprice = 0
                var billtotal = 0
                var profit = 0
                $('#salesTable').dataTable({
                    "bProcessing": true,
                    "serverSide": true,
                    "paging":false,
                    "info":false,
                    "ajax":{
                        url :"sales_list.php",
                        type: "POST",
                        error: function(){
                            $("#salesTable_processing").css("display","none");
                        },
                        "dataSrc":function(json){
                            dealerprice = 0
                            billtotal = 0
                            profit = 0
                            $.each(json.data,function(i,v){
                                $.each(v.items,function(x,y){
                                    dealerprice += parseFloat(y.dealer_price) * parseFloat(y.Qty)
                                })
                            billtotal += parseFloat(v.BillAmount)
                            v.BillAmount = amountFormat(v.BillAmount)
                            v.InvDate = timeFormat(v.InvDate)
                            })
                            profit = parseFloat(billtotal) - parseFloat(dealerprice)
                            $(".dealer_price").text(amountFormat(dealerprice))
                            $(".billtotal").text(amountFormat(billtotal))
                            $(".profit").text(amountFormat(profit))
                            return json.data;
                        }
                    },
                    "columns":[
                        {"data":'InvDate'}, 
                        {"data":'BillNo'},
                        {"data":'Partyname'},
                        {"data":'BillAmount'}
                    ],
                });
            })
        </script>
    </body>
</html>
