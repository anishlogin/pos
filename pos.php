<?php 
include 'db.php';
include 'functions.php';
tryLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta property="in.accordex.poshelper.weighing-machine:enable" content="true">

        <title>POS</title>

        <!-- // FAVICON // -->
        <link rel="icon" href="assets/images/favicon.ico">

        <!-- // EXTERNAL CSS // -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css"> 
        <link rel="stylesheet" href="assets/css/jquery-confirm.min.css">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/pickers/pickadate.css">
        <link rel="stylesheet" href="assets/css/style_pos.css">
        <link rel="stylesheet" href="assets/css/print_pos.css">
    <style type="text/css">
      input[type=number]::-webkit-inner-spin-button, 
      input[type=number]::-webkit-outer-spin-button { 
      -webkit-appearance: none; 
      margin: 0; 
      }
      .hide{
         display: none,
      }

      .col-xs-offset-1 {
        margin-left: 8.33333333%;
      }

      .col-sm-offset-3 {
        margin-left: 25%;
      }
      
      .col-md-offset-4 {
        margin-left: 33.33333333%;
      }

      #Bill {
        width: 453px;
      }
    </style>

</head>

<body id="body">
    <header>
        <div class="container-fluid">
            <div class="col-md-1" >
                <img src="assets/images/company.png" style="background: white;">
            </div>
            <div class="col-md-9">
                <h2>Lakshmi Mini Super Market</h2>
                <h5 style="text-transform: uppercase;">Kunjanvilai, Nagercoil - 629002, Kanyakumari</h5>
            </div>
            <div class="col-md-2" >
                <img src="assets/images/logo.png" style="float: left;background: white;" width="90" height="90">
            </div>
        </div>
    </header>
    <section class="head2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 co-a"><h4>COUNTER : <span id="CounterNo"></span></h4></div>
                <div class="col-md-3">
                    <div class="bill-no">
                        <h5>Bill #</h5>
                        <input type="text" name="" class='billno' readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bill-no">
                        <h5>CASH BILL</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bill-date">
                        <h5>Date</h5>
                        <span size="16" class="form_datetime" id="form_datetime"> </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="price-list">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="last-amt">
                        <div class="f1"><h5>Last Amount</h5><input type="text" placeholder="0.00" class="lastamt"></div>
                    </div>
                    <input type="text" name="" placeholder="Phone Number (CTRL + P)" id="c_number">
                    <input type="text" name="" placeholder="Email Id (CTRL + E)" id="c_email">
                </div>
                <div class="col-md-3">
                    <textarea placeholder="Name & Address (F6)" id="c_address"></textarea>
                </div>
                <div class="col-md-5 cell-row">
                    <div class="co-ad">
                      <a href="./index.php" class="logout"><h5><?php echo($_SESSION['UserName']); ?></h5></a>
                      <input type="hidden" name="operator" id="operator" value="<?php echo($_SESSION['UserName']); ?>">
                      <a href="./logout.php" class="logout"><h5 class="focusLogout">LOGOUT</h5></a>
                    </div>
                    <h1 class="cell cell-middle billTotal" align="center">00.00</h1>
                </div>
            </div>
        </div>
    </section>
    <section>
        <table class="table table-fixed ">
          <thead class="">
            <tr>
              <th class="td-7">S.No <i class="fa fa-sort float-right" aria-hidden="true"></i></th>
              <th class="td-20">Code <i class="fa fa-sort float-right" aria-hidden="true"></i></th>
              <th class="td-33">Description <i class="fa fa-sort float-right" aria-hidden="true"></i></th>
              <th class="st-1">Uom <i class="fa fa-sort float-right" aria-hidden="true"></i></th>
              <th class="st-1">Rate <i class="fa fa-sort float-right" aria-hidden="true"></i></th>
              <!-- <th class="st-1">Discounted Rate <i class="fa fa-sort float-right" aria-hidden="true"></i></th> -->
              <!-- <th class="st-1">Tax <i class="fa fa-sort float-right" aria-hidden="true"></i></th> -->
              <th class="st-1">Qty <i class="fa fa-sort float-right" aria-hidden="true"></i></th>
              <!-- <th class="st-1">Free Qty <i class="fa fa-sort float-right" aria-hidden="true"></i></th> -->
              <th class="st-1">Amount <i class="fa fa-sort float-right" aria-hidden="true"></i></th>
            </tr>
          </thead>
          <tbody id="ItemListBody">
            <tr>
              <td class="td-7" align="center">1</td>
              <td class="td-20"><input type="text" name="productCode" class="prod_code"></td>
              <td class="td-33"></td>
              <td class="td-0">-</td>
              <td align="center" class="prod_rate">00.00</td>
              <!-- <td align="center">00.00</td> -->
              <!-- <td align="center">00.00</td> -->
              <td class="td-2" align="center"><input type="number" name="productQty" class="prod_qty" min="0"></td>
              <!-- <td align="center"><input type="text" name="productQty" class="free_qty"></td> -->
              <td align="right">00.00</td>
            </tr>
            <tr>
              <td class="td-7" align="center">2</td>
              <td class="td-20"><input type="text" name="productCode" class="prod_code"></td>
              <td class="td-33"></td>
              <td class="td-0">-</td>
              <td align="center" class="prod_rate">00.00</td>
              <!-- <td align="center">00.00</td> -->
              <!-- <td align="center">00.00</td> -->
              <td class="td-2" align="center"><input type="number" name="productQty" class="prod_qty" min="0"></td>
              <!-- <td align="center"><input type="text" name="productQty" class="free_qty"></td> -->
              <td align="right">00.00</td>
            </tr>
            <tr>
              <td class="td-7" align="center">3</td>
              <td class="td-20"><input type="text" name="productCode" class="prod_code"></td>
              <td class="td-33"></td>
              <td class="td-0">-</td>
              <td align="center" class="prod_rate">00.00</td>
              <!-- <td align="center">00.00</td> -->
              <!-- <td align="center">00.00</td> -->
              <td class="td-2" align="center"><input type="number" name="productQty" class="prod_qty" min="0"></td>
              <!-- <td align="center"><input type="text" name="productQty" class="free_qty"></td> -->
              <td align="right">00.00</td>
            </tr>
            <tr>
              <td class="td-7" align="center">4</td>
              <td class="td-20"><input type="text" name="productCode" class="prod_code"></td>
              <td class="td-33"></td>
              <td class="td-0">-</td>
              <td align="center" class="prod_rate">00.00</td>
              <!-- <td align="center">00.00</td> -->
              <!-- <td align="center">00.00</td> -->
              <td class="td-2" align="center"><input type="number" name="productQty" class="prod_qty" min="0"></td>
              <!-- <td align="center"><input type="text" name="productQty" class="free_qty"></td> -->
              <td align="right">00.00</td>
            </tr>
            <tr>
              <td class="td-7" align="center">5</td>
              <td class="td-20"><input type="text" name="productCode" class="prod_code"></td>
              <td class="td-33"></td>
              <td class="td-0">-</td>
              <td align="center" class="prod_rate">00.00</td>
              <!-- <td align="center">00.00</td> -->
              <!-- <td align="center">00.00</td> -->
              <td class="td-2" align="center"><input type="number" name="productQty" class="prod_qty" min="0"></td>
              <!-- <td align="center"><input type="text" name="productQty" class="free_qty"></td> -->
              <td align="right">00.00</td>
            </tr><tr>
              <td class="td-7" align="center">6</td>
              <td class="td-20"><input type="text" name="productCode" class="prod_code"></td>
              <td class="td-33"></td>
              <td class="td-0">-</td>
              <td align="center" class="prod_rate">00.00</td>
              <!-- <td align="center">00.00</td> -->
              <!-- <td align="center">00.00</td> -->
              <td class="td-2" align="center"><input type="number" name="productQty" class="prod_qty" min="0"></td>
              <!-- <td align="center"><input type="text" name="productQty" class="free_qty"></td> -->
              <td align="right">00.00</td>
            </tr>
            <tr>
              <td class="td-7" align="center">7</td>
              <td class="td-20"><input type="text" name="productCode" class="prod_code"></td>
              <td class="td-33"></td>
              <td class="td-0">-</td>
              <td align="center" class="prod_rate">00.00</td>
              <!-- <td align="center">00.00</td> -->
              <!-- <td align="center">00.00</td> -->
              <td class="td-2" align="center"><input type="number" name="productQty" class="prod_qty" min="0"></td>
              <!-- <td align="center"><input type="text" name="productQty" class="free_qty"></td> -->
              <td align="right">00.00</td>
            </tr>
            <tr>
              <td class="td-7" align="center">8</td>
              <td class="td-20"><input type="text" name="productCode" class="prod_code"></td>
              <td class="td-33"></td>
              <td class="td-0">-</td>
              <td align="center" class="prod_rate">00.00</td>
              <!-- <td align="center">00.00</td> -->
              <!-- <td align="center">00.00</td> -->
              <td class="td-2" align="center"><input type="number" name="productQty" class="prod_qty" min="0"></td>
              <!-- <td align="center"><input type="text" name="productQty" class="free_qty"></td> -->
              <td align="right">00.00</td>
            </tr>
            <tr>
              <td class="td-7" align="center">9</td>
              <td class="td-20"><input type="text" name="productCode" class="prod_code"></td>
              <td class="td-33"></td>
              <td class="td-0">-</td>
              <td align="center" class="prod_rate">00.00</td>
              <!-- <td align="center">00.00</td> -->
              <!-- <td align="center">00.00</td> -->
              <td class="td-2" align="center"><input type="number" name="productQty" class="prod_qty" min="0"></td>
              <!-- <td align="center"><input type="text" name="productQty" class="free_qty"></td> -->
              <td align="right">00.00</td>
            </tr>
            <tr>
              <td class="td-7" align="center">10</td>
              <td class="td-20"><input type="text" name="productCode" class="prod_code"></td>
              <td class="td-33"></td>
              <td class="td-0">-</td>
              <td align="center" class="prod_rate">00.00</td>
              <!-- <td align="center">00.00</td> -->
              <!-- <td align="center">00.00</td> -->
              <td class="td-2" align="center"><input type="number" name="productQty" class="prod_qty" min="0"></td>
              <!-- <td align="center"><input type="text" name="productQty" class="free_qty"></td> -->
              <td align="right">00.00</td>
            </tr>
            <tr>
              <td class="td-7" align="center">11</td>
              <td class="td-20"><input type="text" name="productCode" class="prod_code"></td>
              <td class="td-33"></td>
              <td class="td-0">-</td>
              <td align="center" class="prod_rate">00.00</td>
              <!-- <td align="center">00.00</td> -->
              <!-- <td align="center">00.00</td> -->
              <td class="td-2" align="center"><input type="number" name="productQty" class="prod_qty" min="0"></td>
              <!-- <td align="center"><input type="text" name="productQty" class="free_qty"></td> -->
              <td align="right">00.00</td>
            </tr>
            <tr>
              <td class="td-7" align="center">12</td>
              <td class="td-20"><input type="text" name="productCode" class="prod_code"></td>
              <td class="td-33"></td>
              <td class="td-0">-</td>
              <td align="center" class="prod_rate">00.00</td>
              <!-- <td align="center">00.00</td> -->
              <!-- <td align="center">00.00</td> -->
              <td class="td-2" align="center"><input type="number" name="productQty" class="prod_qty" min="0"></td>
              <!-- <td align="center"><input type="text" name="productQty" class="free_qty"></td> -->
              <td align="right">00.00</td>
            </tr>
          </tbody>
        </table>
    </section>
    <section class="main-list">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="fun-button">
                        <button onclick="location.reload()">New [F3]</button>
                        <button class="printThis">Print [F5]</button>
                        <button class="pending">Pending [F10]</button>
                        <button class="showPending">Pending List [F11]</button>
                        <!-- <button>Exit [F10]</button> -->
                    </div>
                    <div class="fun">
                        <h5>F1 - Product List</h5>
                        <h5>F2 - Close Shift</h5>
                        <h5>F12 - Weighting Scale Reading</h5>
                        <h5>F8 - Rate Check</h5>
                        <h5>Ctrl+A - Add Item</h5>
                        <h5>Ctrl+Q - Item Qty Edit</h5>
                        <h5>Ctrl+F - Full Screen</h5>
                        <h5>Ctrl+E - Exit</h5>
                        <!-- <h5>Ctrl+D - Cash / Gift Card</h5> -->
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="fun-count">
                        <div class="f1"><h5>Discount - [F12]</h5><input type="text" placeholder="N" id="discountFlag" maxlength="1" value="N"></div>
                        <div class="f1"><h5>No. of Bags - [F7]</h5><input type="number" placeholder="0" id="noBags" min="0" max="9"></div>
                        <h5 class="gift"> Cash / Card [ Ctrl+Z ]</h5>
                    </div>
                   <!--  <fieldset>
                        <legend>Sodexo Coupon [ Ctrl + S ]</legend>
                        <h6>Coupon Amount</h6><input type="text" name="">
                    </fieldset> -->
                </div>
                <div class="col-md-3">
                    <fieldset>
                        <legend>Coupons [ Ctrl + C ]</legend>
                        <table class="gst-amt">
                        <thead>
                            <tr>
                                <th>Coupon</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>&nbsp</td>
                                <td>&nbsp</td>
                            </tr>
                            <tr>
                                <td>&nbsp</td>
                                <td>&nbsp</td>
                            </tr>
                            <tr>
                                <td>&nbsp</td>
                                <td>&nbsp</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="tot-count">
                            <div class="f1"><h5>Total</h5><input type="text" placeholder="0.00" class="netTotal"></div>
                            <div class="f1"><h5>To Pay</h5><input type="text" placeholder="0.00"></div>
                    </div>
                    </fieldset>
                </div>
                <div class="col-md-3">
                    <div class="fun-amt">
                        <div class="f1"><h5>Sub Total</h5><input type="text" placeholder="0.00" class="subTotal" readonly ></div>
                        <div class="f1"><h5>GST</h5><input type="text" placeholder="0.00" class="gst" readonly ></div>
                        <div class="f1"><h5>CESS</h5><input type="text" placeholder="0.00" class="cess" readonly ></div>
                        <div class="f1"><h5>Others</h5><input type="text" placeholder="0.00" ></div>
                        <div class="f1"><h5>Discount</h5><input type="text" placeholder="0.00"  class="discountAmt" style="background: limegreen;"></div>
                        <div class="f1"><h5>Total</h5><input type="text" placeholder="0.00" class="grandTotal" readonly ></div>
                        <div class="f1"><h5>Round Off</h5><input type="number" placeholder="" class="roundof" readonly style="background: yellow;"></div>
                        <div class="f1"><h5>Net Total</h5><input type="text" placeholder="0.00" class="netTotal" readonly style="background: red;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" name="paid" id="paid" value="">
    <input type="hidden" name="lasttime" id="lasttime" value="">
    <input type="hidden" name="posid" id="posid" value="">



    <!-- // JAVASCRIPT START HEAR // -->
  <script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
  <script type="text/javascript" src="assets/js/popper.min.js"></script>
  <script type="text/javascript" src="assets/js/tether.min.js"></script>
  <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="assets/js/jquery-confirm.min.js"></script>
  <script type="text/javascript" src="assets/js/plugins/template7/template7.min.js"></script>
  <script type="text/javascript" src="assets/js/jQuery.print.min.js"></script>
  <script type="text/javascript" src="assets/js/pickers/moment-with-locales.min.js"></script>
  <script type="text/javascript" src="assets/js/pickers/bootstrap-datetimepicker.min.js"></script>
  <!-- <script type="text/javascript" src="assets/js/pickers/picker.js"></script> -->
  <!-- <script type="text/javascript" src="assets/js/pickers/picker.date.js"></script> -->
  <!-- <script type="text/javascript" src="assets/js/pickers/picker-date-time.min.js"></script> -->

  <script type="text/javascript" src="assets/js/ClassDom.js"></script>
  <script type="text/javascript" src="assets/js/ClassServer.js"></script>
  <script type="text/javascript" src="assets/js/functions.js"></script>
    <script type="text/javascript">
        $(".form_datetime").datetimepicker({format: 'dd-mm-yyyy hh:ii'});
    </script>
    <script type="text/javascript">

    //         var $logo = $('#scroll_top');
    // $(document).scroll(function() {
    //     if($(this).scrollTop()> 100)
    //     {   //alert("scop");
    //  //  $logo.css({display: $(this).scrollTop() > 100? "block":"none"});
    //      $('#scroll_top').css("display", "block");
    //     }else{
    //          $('#scroll_top').css("display", "none");
    //     }

    // });

    </script>
    <modals>
      <div class="modal fade" id="productListModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Product List</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body list_product">
              <div>
                <input type="text" name="search" placeholder="Enter Product Code or Name" id="search">
              </div>
              <div>
                <input type="hidden" name="pageNum" id="pageNum" value="1">
                <input type="hidden" name="searchPageNum" id="searchPageNum" value="1">
                <table class="table">
                  <thead class="">
                    <tr>
                      <th>Code <i class="fa fa-sort float-right" aria-hidden="true"></i></th>
                      <th class="td-33">Description <i class="fa fa-sort float-right" aria-hidden="true"></i></th>
                      <th class="st-1">Uom <i class="fa fa-sort float-right" aria-hidden="true"></i></th>
                      <th class="st-1">Rate <i class="fa fa-sort float-right" aria-hidden="true"></i></th>
                    </tr>
                  </thead>
                  <tbody id="ProductListBody" style="overflow-y: visible">

                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
     </div>
     <div class="modal fade" id="rateCheckModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Check Rate</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
                <fieldset class="amount-rend">
                  <div id="rateCheck" class="row">
                    <div class="col-md-6">
                      <span>Item Code :</span>
                    </div>
                    <div class="col-md-6">
                          <input type="text" name="rateCheck_prod_code" class="rateCheck_prod_code">
                    </div>
                    <div class="col-md-6">
                      <span>Item Name :</span>
                    </div>
                    <div class="col-md-6">
                          <input type="text" name="rateCheck_prod_name" class="rateCheck_prod_name" readonly>
                    </div>
                    <div class="col-md-6">
                      <span>Rate :</span>
                    </div>
                    <div class="col-md-6">
                          <input type="text" name="rateCheck_prod_rate" class="rateCheck_prod_rate" readonly>
                    </div>
                    <div class="col-md-6">
                      <span>Tax :</span>
                    </div>
                    <div class="col-md-6">
                          <input type="text" name="rateCheck_prod_tax" class="rateCheck_prod_tax" readonly>
                    </div>
                    <div class="col-md-6">
                      <span>Qty :</span>
                    </div>
                    <div class="col-md-6">
                          <input type="text" name="rateCheck_prod_qty" class="rateCheck_prod_qty">
                    </div>
                    <div class="col-md-6">
                      <span>Amount :</span>
                    </div>
                    <div class="col-md-6">
                          <input type="text" name="rateCheck_prod_amount" class="rateCheck_prod_amount" readonly>
                    </div>
                          <input type="hidden" name="rateCheckProd" id="rateCheckProd">
                  </div>
             </fieldset>
            </div>
            <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
     </div>

     <div class="modal fade" id="listFreeItemsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLongTitle">Free Items</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
              <div>
                <table>
                  <thead>
                    <tr>
                      <td>Item Code</td>
                      <td>Name</td>
                      <td>Qty</td>
                    </tr>
                  </thead>
                  <tbody id="freeItems">

                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
     </div>
     <div class="modal fade" id="billPayModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body">
                  <form class="form-horizontal">
                    <fieldset class="amount-rend">
                        <!-- Form Name -->
                        <legend>Cash Rendering</legend>
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-8 control-label" for="payable_amt">Payable Amount</label>
                          <div class="col-md-4">
                             <input id="payable_amt" name="payable_amt" type="text" placeholder="Payable Amount" class="form-control input-md" readonly>
                          </div>
                        </div>
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-8 control-label" for="discount">Discount</label>
                          <div class="col-md-4">
                             <input id="discount" name="discount" type="text" placeholder="Discount" class="form-control input-md" readonly>
                          </div>
                        </div>
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-8 control-label" for="net">Net Amount</label>
                          <div class="col-md-4">
                            <input id="net" name="net" type="text" placeholder="Net Amount" class="form-control input-md" readonly>
                          </div>
                        </div>
                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-8 control-label" for="pay_type">Mode[F4]</label>
                          <div class="col-md-4">
                            <select id="pay_type" name="pay_type" class="form-control">
                              <option value="cash">Cash</option>
                              <option value="card">Card</option>
                              <option value="paytm">Paytm</option>
                            </select>
                          </div>
                        </div>
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-8 control-label" for="amount_paid">Customer Paid</label>
                          <div class="col-md-4">
                             <input id="amount_paid" name="amount_paid" type="number" placeholder="Amount Paid" class="form-control input-md cashreceived">
                          </div>
                        </div>
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-8 control-label" for="balance">Balance</label>
                          <div class="col-md-4">
                            <input id="balance" name="balance" type="text" placeholder="Balance" class="form-control input-md balance" readonly>

                          </div>
                        </div>
                    </fieldset>
                  </form>

            </div>
            <div class="modal-footer">
             <button type="button" class="btn btn-primary" data-dismiss="modal" id="amountPaid">Paid</button>
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
     </div>
     <div class="modal fade" id="pendingListModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Pending List</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body list_pending list_product">
              <div class="row">
                <div class="col-md-6">
                  <input type="text" name="search" placeholder="Enter Bill No Here" id="pendingSearch" class="popupinput">
                </div>
                <div class="col-md-6">
                  <input type="text" name="billDate" placeholder="Select Bill Date" id="billDate" class="popupinput pickadate-dropdown">
                </div>
              </div>
              <div>
                <input type="hidden" name="pageNum" id="pendingPageNum" value="1">
                <input type="hidden" name="searchPageNum" id="pendingSearchPageNum" value="1">
                <table class="table">
                  <thead class="">
                    <tr>
                      <th>Bill No<i class="fa fa-sort float-right" aria-hidden="true"></i></th>
                      <th>Party Name<i class="fa fa-sort float-right" aria-hidden="true"></i></th>
                      <th class="td-33">Counter No<i class="fa fa-sort float-right" aria-hidden="true"></i></th>
                      <th class="st-1">Total Amount<i class="fa fa-sort float-right" aria-hidden="true"></i></th>
                      <th class="st-1">Amount Paid<i class="fa fa-sort float-right" aria-hidden="true"></i></th>
                      <th class="st-1">Balance<i class="fa fa-sort float-right" aria-hidden="true"></i></th>
                    </tr>
                  </thead>
                  <tbody id="pendingListBody" style="overflow-y: visible">

                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
     </div>
    </modals>
    <div id="tab-bill" style="display: none;width: 100%">

    </div>
    <div id="tab-shift-close" style="display: none;width: 100%">

    </div>
      <script type="text/html" id="Template-Product-List">
        {{#each items}}
        <tr tabindex="0" class="searchProduct" data-code="{{code}}">
          <td>{{code}}</td>
          <td class="td-33">{{name}}</td>
          <td>{{unit}}</td>
          <td align="center">{{price}}</td>
        </tr>
         {{/each}}
      </script>
      <script type="text/html" id="Template-Counter-List">
          {{#each counters}}
            <div style="display: inline-block;">
              <button class="setMyCounter btn btn-primary" data-counterid="{{counterid}}" data-counterkey='{{counterkey}}'>
                Counter {{counterid}}
              </button>
            </div>
          {{/each}}
      </script>
      <script type="text/html" id="Template-Pending-List">
        {{#each items}}
        <tr data-posid = "{{posid}}" class="loadBill" tabindex="0">
          <td>{{BillNo}}</td>
          <td>{{Partyname}}</td>
          <td class="td-33">{{counterno}}</td>
          <td>{{BillAmount}}</td>
          <td>{{paidamount}}</td>
          <td align="center">{{changeamount}}</td>
        </tr>
         {{/each}}
      </script>
      <script type="text/html" id="Template-Shift-Close">
        {{#each shiftClose}}
          <section >
              <div class="tab-color">
                  <div class="">
                  <table class="table-bordered">
                      <tr>
                          <td class=" tb-center">
                              <p class="company-name">Lakshmi Mini Super Market</p>
                              <p>Kunjanvilai</p>
                <p>Nagercoil- 629002</p>
                              <p style="color:#000;">Phone: 9739190398</p>
                          </td>
                      </tr>
                  </table>
              </div>
              <div class="">
                  <table class="table-bordered">
                      <tr>
                          <td class=" title">
                              <p><strong><u>Shift Cash Details</u></strong></p>

                          </td>
                      </tr>
                  </table>
              </div>
                <div class="">
                  <table class="table-bordered cashdetails">
                      <tr>
                          <td style="width: 40%">
                              <p>Operator</p>

                          </td>
                          <td style="width: 10%">
                              <p>:</p>
                          </td>
                          <td >
                              <p>{{CreatedBy}}</p>

                          </td>
                      </tr>
                      <tr>
                          <td >
                              <p>Date</p>

                          </td>
                          <td style="width: 2%">
                              <p>:</p>
                          </td>
                          <td >
                              <p>{{date}}</p>

                          </td>
                      </tr>
                      <tr>
                          <td >
                              <p>Counter NO</p>

                          </td>
                          <td style="width: 2%">
                              <p>:</p>
                          </td>
                          <td >
                              <p>{{counterno}}</p>

                          </td>
                      </tr>
                      <tr>
                          <td class=" ">
                              <p>Shift Time</p>

                          </td>
                          <td style="width: 2%">
                              <p>:</p>
                          </td>
                          <td class=" ">
                              <p>{{time}}</p>

                          </td>
                      </tr>
                  </table>
              </div>
              <table class="table-bor-right space">
                  <tr class=" bor-top" style="height: 2px">
                      <td colspan="4" style="text-align: left; padding-top:6px"><p><u>Denomination</u></p></td>

                  </tr>
                  <tr style="height: 70px;">
                      <td class="denom" style="width:14%"><p>2000</p></td>
                      <td  style="width:20%;text-align: center;"><p>x</p></td>
                      <td style="text-align: right;width: 20%;"><p>=</p></td>
                      <td  style="text-align: right;"><p></p></td>
                  </tr>
                  <tr style="height: 70px;">
                      <td class="denom"><p>1000 </p></td>
                       <td style="width:20%;text-align: center;"><p>x</p></td>
                      <td style="text-align: right;width: 20%;"><p>=</p></td>
                        <td  style="text-align: right;"><p></p></td>
                  </tr>
                  <tr style="height: 70px;">
                      <td class="denom"><p>500 </p></td>
                       <td style="width:20%;text-align: center;"><p>x</p></td>
                     <td style="text-align: right;width: 20%;"><p>=</p></td>
                        <td  style="text-align: right;"><p></p></td>
                  </tr>
                  <tr style="height: 70px;">
                      <td class="denom"><p> 200 </p></td>
                      <td style="width:20%;text-align: center;"><p>x</p></td>
                     <td style="text-align: right;width: 20%;"><p>=</p></td>
                       <td  style="text-align: right;"><p></p></td>
                  </tr>
                   <tr style="height: 70px;">
                      <td class="denom"><p>100 </p></td>
                       <td style="width:20%;text-align: center;"><p>x</p></td>
                      <td style="text-align: right;width: 20%;"><p>=</p></td>
                       <td  style="text-align: right;"><p></p></td>
                  </tr>
                  <tr style="height: 70px;">
                      <td class="denom"><p>50 </p></td>
                       <td style="width:20%;text-align: center;"><p>x</p></td>
                      <td style="text-align: right;width: 20%;"><p>=</p></td>
                       <td  style="text-align: right;"><p></p></td>
                  </tr>
                  <tr style="height: 70px;">
                      <td class="denom"><p>20 </p></td>
                       <td style="width:20%;text-align: center;"><p>x</p></td>
                      <td style="text-align: right;width: 20%;"><p>=</p></td>
                      <td  style="text-align: right;width: 36%;"><p></p></td>
                  </tr>
                  <tr style="height: 70px;">
                      <td class="denom"><p>10 </p></td>
                        <td style="width:20%;text-align: center;"><p>x</p></td>
                      <td style="text-align: right;width: 20%;"><p>=</p></td>
                        <td  style="text-align: right;"><p></p></td>
                  </tr>
                 <tr style="height: 70px;">
                      <td class="denom"><p>Coins </p></td>
                       <td style="width:20%;text-align: center;"><p>x</p></td>
                       <td style="text-align: right;width: 20%;"><p>=</p></td>
                         <td  style="text-align: right;"><p></p></td>
                  </tr>
              </table>
              <table class=" space">
                 <tr class="bor-bottom" style="height: 10px;">
                      <td style="border-top: 1px dashed;padding-bottom: 16px;width: 68%;"><p>Sub Total</p></td>
                      <td >
                      </td>
                  </tr>
                  <tr class="bor-bottom" style="height: 10px;">
                      <td  style="padding-bottom: 16px;width: 68%"><p>Office</p></td>
                      <td >
                      </td>
                  </tr>
                  <tr class="bor-bottom" style="height: 100px;">
                      <td style="width: 68%"><p>Card Bill</p></td>
                      <td >
                        {{card}} ({{countcard}})
                      </td>
                  </tr>
                  <tr  style="height: 100px;">
                      <td style="height: 100px;"></td>
                      <td style="height: 100px;">

                      </td>
                  </tr>
                  <tr  style="height: 100px;">
                      <td style="height: 100px;"></td>
                      <td style="height: 100px;">

                      </td>
                  </tr>
                  <tr  style="height: 100px;">
                      <td style="height: 100px;"></td>
                      <td style="height: 100px;">

                      </td>
                  </tr>
              </table>

          </section>
          {{/each}}
      </script>
      <script type="text/html" id="template-item-bill">

        <div id="Bill">
        <div class="whole-sec">
          <div class="one">
            <h3 style="color:#000;">Lakshmi Mini Super Market</h3>
      <p style="color:#000;">Kunjanvilai</p>
      <p style="color:#000;">Nagercoil- 629002</p>
            <p style="color:#000;">Phone: 9600973424</p>
            <p style="color:#000;">GSTIN: 33BDQPA4212A1ZD</p>
            <P style="color:#000;">Cash Bill({{type}})</P>
          </div>
          <div class="two">
            <div class="two-one">
              <p style="color:#000;">Bill No: {{bill}}</p>
              <p style="color:#000;">Operator:{{operator}}
            </div>
            <div class="two-two">
              <p style="font-size: 20px;margin: 0;color:#000;">Date : {{time}}</p>
            </div>
          </div>
          <table style="width:100%;margin-bottom:9px;">
            <thead>
              <tr>
                <th style="width: 2%;">Sl</th>
                <th  style="width: 35%;color:#000;text-align: center;">Item</th>
                <th style="width: 25%;color:#000;text-align: center;">Rate</th>
                <th style="width: 18%;color:#000;text-align: center;">Qty</th>
                <th style="width: 20%;color:#000;text-align: center;">Amt</th>
              </tr>
            </thead>
            {{#each items}}
              <tr>
                <td style="color:#000;">{{ serial }}</td>
                <td style="text-transform: capitalize;color:#000;"><p>{{name}}</p></td>
                <td style="color:#000;text-align: right;">{{$price$ price}}/{{unit}}</td>
                <td style="color:#000;text-align: right;">{{qty}}</td>
                <td style="text-align: right;color:#000;">{{$price$ total}}</td>
              </tr>
            {{/each}}
            <tbody>
              <tr>
                <td colspan="4" style="text-align: center;color:#000;">Sub Total</td>
                <td style="text-align: right;color:#000;">{{$price$ subtotal}}</td>
              </tr>
              <tr>
                <td colspan="4" style="text-align: center;color:#000;">Discount</td>
                <td style="text-align: right;color:#000;">{{$price$ disc}}</td>
              </tr>
              <tr>
                <td colspan="4" style="text-align: center;color:#000;">Roundoff</td>
                <td style="text-align: right;color:#000;">{{$price$ round}}</td>
              </tr>
            </tbody>
            <tr>
              <td colspan="2" style="color:#000;">Counter : {{counter}}</td>
              <td colspan="2" style="padding-left: 54px;color:#000;">Total:Rs</td>
              <td style="text-align: right;color:#000;"><b>{{$price$ grand}}</b></td>
            </tr>
          </table>
          <div class="last">
            <p style="color:#000;">Tot Items :{{qty}} <span style="float: right;margin-right: 10px;color:#000;">Paid:{{$price$ amountpaid}} <span style="margin-left: 40px;color:#000;"> Bal: {{$price$ balance}}</span></span></p>
          </div>
          <p style="text-align: center;margin: 0;font-size: 20px;color:#000;">THANK YOU,VISIT AGAIN &#128522;</p>
          <p style="text-align: center;margin: 0;font-size: 12px;color:#000;">SYSTEM IS UNDER TESTING, IF YOU HAVE QUERIES CONTACT US</p>
        </div>
      </div>
      </script>
      <script type="text/html" id="Template-Saleprice-List">
          <SELECT class="thisSalePrice">
            {{#each items}}
               <option value="{{saleprice}}">{{Mrp}}</option>
             {{/each}}
          </SELECT>
             <input type="hidden" name="item_id" class="item_id" value="{{id}}">
      </script>
      <script type="text/html" id="Template-FreeItems-List">
        {{#each items}}
        <tr>
          <td>{{freeitemcode}}</td>
          <td>{{freename}}</td>
          <td>{{freeqty}}</td>
        </tr>
        {{/each}}
      </script>
<script type="text/javascript">

</script>
<input type="hidden" id="in-accordex-poshelper-field-weighing-machine">

</body>
</html>
