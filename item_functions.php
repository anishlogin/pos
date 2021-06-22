<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include "db.php";
    include "functions.php";
    tryLogin();

    if (isset($_GET["barcode"])){

    	getBarcodeNum($_GET['barcode'],$conn);
    }

    function getBarcodeNum($barcode,$conn){

        try{
            $sql = "select * from products where code = '$barcode'";
            
            $result = mysqli_query($conn,$sql);
            
            if($result->num_rows == 0){
               echo "success,$barcode";
            }else{
               print_r (['msg'=>'fail','barcode'=>'']);

            }

           
            
        }catch(Exception $e){
            echo '<b style="text-align:center">'.$e->getMessage().'</b>';
            echo '<a href="'.$_SERVER['HTTP_REFERER'].'">Go Back</a>';
        }
       
  	}

?>