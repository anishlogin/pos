
<?php
    include "db.php";
    include "functions.php";
	error_reporting(0);
    tryLogin();
    
    if(isset($_GET["id"])){

       try {
		 
                $sql = "delete from products where id=".$_GET['id'];
                $result = mysqli_query($conn, $sql);
                header('Location: itemlist.php');
                die();
           
        } catch (Exception $e) {
            echo '<b style="text-align:center">'.$e->getMessage().'</b>';
           echo '<a href="'.$_SERVER['HTTP_REFERER'].'">Go Back</a>';
            
        }
    }
?>